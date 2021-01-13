package main

import (
	"encoding/json"
	//"errors"
	"fmt"
	//"io"
	"log"
	"net/http"
	"strconv"
	//"gorm.io/driver/mysql"
	//"gorm.io/gorm"
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

func failOnError(err error, msg string) {
	if err != nil {
		log.Fatalf("%s: %s", msg, err)
	}
}

func Rabbit(w http.ResponseWriter, r *http.Request) {
	fmt.Println("r.Form=", r.Form) //这些信息是输出到服务器端的打印信息 , Get参数
	
	id,_ := strconv.Atoi(r.Form["id"][0])
	num,_ := strconv.Atoi(r.Form["num"][0])	
	
	username := r.Form["username"][0]
	phonenumber := r.Form["phonenumber"][0]
	address := r.Form["address"][0]
	remark := r.Form["remark"][0]
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

	message := SqlMessage{}
	message.ID = id
	message.Num = num
	message.Username = username
	message.Phonenumber = phonenumber
	message.Address = address
	message.Remark = remark
	body, err := json.Marshal(message)
    if err != nil {
        fmt.Println("生成json字符串错误")
    }
	err = ch.Publish(
		"",     // exchange
		q.Name, // routing key
		false,  // mandatory
		false,  // immediate
		amqp.Publishing{
			ContentType: "text/plain",
			Body:        []byte(body),
		})
	log.Printf(" [x] Sent %s", string(body))
	failOnError(err, "Failed to publish a message")
}

func sayMore(w http.ResponseWriter, r *http.Request) {
	r.ParseForm() //解析参数，默认是不会解析的
	Rabbit(w,r)
}
func main() {
	http.HandleFunc("/", sayMore)            //设置访问的路径
	err := http.ListenAndServe(":9000", nil) //设置监听的端口
	if err != nil {
		log.Fatal("ListenAndServe: ", err)
	}
}