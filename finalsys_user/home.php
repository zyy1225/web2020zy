<!DOCTYPE html>
<?php 
    require_once 'common.php';
    $obj=new Common();
    $res=$obj->check();
//   echo($res);
    if($res==false)
    {
        ?> 
        <script type="text/javascript">
        window.location.href="/finalsys_user/index.php";
        </script> 
        <?php 
    }
?>
<html lang="zh-ch">
	<head>
		<meta charset="utf-8" culture="zh-CN" uiCulture="auto" requestEncoding="utf-8">
		<title>商品秒杀</title>
		<!-- 开发环境版本，包含了有帮助的命令行警告 -->
        <!-- import CSS -->
        <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
        <link rel="stylesheet" href="./css/home.css">
        <!-- import Vue before Element -->
        <script src="https://unpkg.com/vue/dist/vue.js"></script>
        <!-- import JavaScript -->
        <script src="https://unpkg.com/element-ui/lib/index.js"></script>
        <!-- jquery -->
        <script src="https://ajax.aspnetcdn.com/ajax/jquery/jquery-3.5.1.min.js"></script>
        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
	</head>
 
	<body>
        <div class="store-register" id="app">
            <div class="register-bg">
                <!-- 登录表单 -->
                <el-form :model="registerForm" ref="RegisterFormRef" :rules="registerFormRules" label-width="80px" class="register_form">
                <br>
	    
                <div id="timer" style="color:red"></div>
                <div id="warring" style="color:red"></div>
                <br>
                <div>   
                    <el-carousel indicator-position="outside" :interval="4000" arrow="always" weight="500px" height="313px">
                        <el-carousel-item v-for="item in imgList" :key="item.id">
                             <img :src="item.idView" class="image">
                        </el-carousel-item>
                    </el-carousel>
                </div>
                <el-form-item label="选择数目" prop="num">
                <el-input-number v-model="registerForm.num" :min="1" :max="5" label="描述文字"></el-input-number>
                </el-form-item>
                    <!-- 用户名 -->
                    <el-form-item label="用户名" prop="username">
                        <el-input v-model="registerForm.username" prefix-icon="iconfont icon-user"></el-input>
                    </el-form-item>
                    <!-- 联系方式 -->
                    <el-form-item label="联系方式" prop="phonenumber">
                        <el-input v-model="registerForm.phonenumber" placeholder="请输入联系方式"  prefix-icon="iconfont icon-phonenumber"></el-input>
                    </el-form-item>
                     <!-- 地址 -->
                    <el-form-item label="收货地址" prop="address">
                        <el-input v-model="registerForm.address" placeholder="请输入送货地址" prefix-icon="iconfont icon-address"></el-input>
                    </el-form-item>
                     <!-- 邮箱 -->
                    <el-form-item  label="备注" prop="remark">
                        <el-input v-model="registerForm.remark" placeholder="备注" prefix-icon="iconfont icon-remark"></el-input>
                    </el-form-item>
                    <!-- 按钮 -->
                    <el-form-item class="btns" label-width="0px">
                        <el-button id="ok" type="primary" @click="submit">确定购买</el-button>
                        <el-button type="info" @click="back">返回</el-button>
                    </el-form-item>
                </el-form>
            </div>
        </div>
        <script type="text/javascript">               
        </script>
        <script>
        new Vue({
            el: '#app',
            data: function() {  
                $("#ok").attr('disabled',false); 
                 var validphonenumber = (rule, value, callback) => {
                    	  if (value === '') {
                             callback(new Error('请输入联系方式'))
                             // password 是表单上绑定的字段
                          }
                          else{
                              let num = Number(value);  //将字符串转换为数字
                              if(Number.isNaN(num)) {
                                callback(new Error('请输入数字!'))
                              }
                              else if(value.length!=11){
                                  callback(new Error('请输入11位手机号！'))
                              }
                              else {
                               	 callback()
                              }
                          }
                        };
               
                    function getCookie(name) { 
                    var arr = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
                        if(arr != null) 
                        {
                          console.log(arr);
                          return arr[2];
                        }
                        return null;
                    }
                    var name=unescape(getCookie('username')); //cookie赋值给变量。
                    var number=unescape(getCookie('phonenumber'));
                    //var name=document.cookie('username');
                return {
                    //数据绑定
                    imgList: [
                        {id:0,idView:'/finalsys_user/img/01.jpg'},
                        {id:1,idView:'/finalsys_user/img/02.jpg'},
                        {id:2,idView:'/finalsys_user/img/03.jpg'}, 
                    ],
                    num:1,
                    show: {
                        diplay: 'blok',
                    },
                    registerForm: {
                        num:1,
                        username:name,
                        phonenumber:number,
                        address:'',
                        remark:''
                    },
                    //表单验证规则
                    registerFormRules: {
                        username: [{
                                required: true,
                                message: '请输入登录名',
                                trigger: 'blur'
                            },
                            {
                                min: 2,
                                max: 10,
                                message: '登录名长度在 3 到 10 个字符',
                                trigger: 'blur'
                            }
                        ],
                        phonenumber:[{
                                required: true,
                                validator: validphonenumber,
                                trigger: 'blur'
                            }
                        ],
                        address: [{
                                required: true,
                                message: '收货地址为必填项！',
                                trigger: 'blur'
                            }
                        ],
                        remark: [{
                                required: false,
                                trigger: 'blur'
                            }
                        ]
                    }
                }
            },
            methods: {
                handleChange(value) {
                console.log(value);
              },
                submit: function (){
                    //点击登录的时候先调用validate方法验证表单内容是否有误
                    this.$refs.RegisterFormRef.validate(async valid => {
                        console.log(this.registerFormRules)
                        //如果valid参数为true则验证通过
                        if (!valid) {
                            console.log("error");
                            return
                        }
                        console.log("hello");
                        var params = new URLSearchParams();
                        params.append('num',this.registerForm.num);
                        params.append('username',this.registerForm.username);
                        params.append('phonenumber',this.registerForm.phonenumber);
                        params.append('address',this.registerForm.address);
                        params.append('remark',this.registerForm.remark);
                        console.log(this.registerForm.num);
                        //window.location.href="/finalsys_user/deal.php"; 
                        
                        axios.post('deal.php', params).then(res => 
                        {
                            if(res.data==1)
                            {
                                console.log("购买成功");
                                window.location.href="/finalsys_user/submit/submit_success.html";
                            }
                            else if(res.data==2)
                            {
                                console.log("购买失败");
                                window.location.href="/finalsys_user/submit/submit_failed.html";
                            }
                            else if(res.data==3)
                            {
                                console.log("库存不足");
                                window.location.href="/finalsys_user/submit/notenough.html";
                            }
                        });
                    })
                    
                },
                back:function()
                {
                    window.location.href="/finalsys_user/choose.php";
                },
                changBg() {
                    // alert(11);
                    // const bglogin = document.getElementsByClassName("bg-login");
                    // console.log(bglogin);
                   
                    $(".bg-register>li:eq(" + this.index + ")").fadeIn("3000").siblings().fadeOut("3000");
                    console.log(this.index);
                }
            },
            created() {
                this.index = this.bgImages.length
                setInterval(this.changBg, 7000);
            }
        })
        
    </script>
    </body>
</html> 
