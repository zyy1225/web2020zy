package main

import (
	"encoding/json"
	"errors"
	"fmt"
	//"io"
	"log"
	//"net/http"
	//"strconv"
	"gorm.io/driver/mysql"
	"gorm.io/gorm"
	"github.com/streadway/amqp"
)
//sql_message struct
type SqlMessage struct {
	ID    			int			//ID
	Num   		 	int			//购买数量
	Username 		string 		//姓名
	Phonenumber 	string   	//手机号
	Address 		string		//地址
	Remark 			string		//备注
}



//purchaser_info ...
type Purchaser_info struct {
	ID    			int    	  `gorm:"primaryKey"`
	Num   		 	int	  	  `gorm:"column:num"`
	Username 		string    `gorm:"column:username"`
	Phonenumber 	string    `gorm:"column:phonenumber"` 
	Address		 	string    `gorm:"column:address"` 
	Remark		 	string    `gorm:"column:remark"` 
}

//purchaser_info ...
type Goods struct {
	ID    			int    	  `gorm:"primaryKey"`
	Name   		 	string	  `gorm:"column:name"`
	Num 			int		  `gorm:"column:num"`
	Rest_num 		int   	  `gorm:"column:rest_num"` 
	Description		string    `gorm:"column:description"` 
}

func failOnError(err error, msg string) {
	if err != nil {
		log.Fatalf("%s: %s", msg, err)
	}
}

func choose(id int, num int, username string,phonenumber string,address string,remark string ) string{
	//连接数据库

	dsn := "websys:T4acAj7wce@tcp(127.0.0.1:3306)/websys?charset=utf8mb4&parseTime=True&loc=Local"
	db, err := gorm.Open(mysql.Open(dsn), &gorm.Config{})
	if err != nil {
		fmt.Println(err)
	}
	fmt.Println("------------连接数据库成功-----------")
	isok := 1

	
	//检查是否合法

	// 开始事务
	tx := db.Begin()
	if isok == 1{
		purchaser_info:=&Purchaser_info{
			ID:id,
			Num:num,
			Username:username,
			Phonenumber:phonenumber,
			Address:address,
			Remark:remark,
		  }	
		tx.Table("websys_purchaser_info").Create(purchaser_info)
		goods := Goods{}
		errGoods := tx.Table("websys_goods").Where("id = ?",1).First(&goods).Error
		if errors.Is(errGoods, gorm.ErrRecordNotFound) { 
			return "无信息！"
		}
		tx.Table("websys_goods").Where("id = ?",1).Update("rest_num", goods.Rest_num-num)
	}

	// 否则，提交事务
	tx.Commit()
	return "写入数据库成功！"
}


func main() {
	conn, err := amqp.Dial("amqp://guest:guest@localhost:5672/")
	failOnError(err, "Failed to connect to RabbitMQ")
	defer conn.Close()

	ch, err := conn.Channel()
	failOnError(err, "Failed to open a channel")
	defer ch.Close()

	q, err := ch.QueueDeclare(
		"sys_info", // name
		false,   // durable
		false,   // delete when unused
		false,   // exclusive
		false,   // no-wait
		nil,     // arguments
	)
	failOnError(err, "Failed to declare a queue")

	msgs, err := ch.Consume(
		q.Name, // queue
		"",     // consumer
		true,   // auto-ack
		false,  // exclusive
		false,  // no-local
		false,  // no-wait
		nil,    // args
	)
	failOnError(err, "Failed to register a consumer")



	forever := make(chan bool)

	go func() {
		for d := range msgs {
			log.Printf("Received a message: %s", d.Body)
			
			var mes SqlMessage
			//1.Unmarshal的第一个参数是json字符串，第二个参数是接受json解析的数据结构
			err = json.Unmarshal(d.Body, &mes)
			if err != nil {
				fmt.Printf("json.Unmarshal failed, err:%v\n", err)
				return
			}
			fmt.Printf("mes:%#v\n", mes)
 			res := choose(mes.ID, mes.Num, mes.Username, mes.Phonenumber, mes.Address,mes.Remark)
			fmt.Printf("res:%s\n", res)
		}
	}()

	log.Printf(" [*] Waiting for messages. To exit press CTRL+C")
	<-forever

}