<!DOCTYPE html>
<?php 
    require_once 'common.php';
    $obj=new Common();
    $res=$obj->check();
//   echo($res);
    if($res==true)
    {
        ?> 
        <script type="text/javascript">
        window.location.href="/finalsys_user/choose.php";
        </script> 
        <?php 
    }
?>


<html lang="zh-ch">
	<head>
		<meta charset="utf-8">
		<title>登录</title>
		<!-- 开发环境版本，包含了有帮助的命令行警告 -->
        <!-- import CSS -->
        <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
        <!-- import Vue before Element -->
        <link rel="stylesheet" href="./css/login.css">
        <script src="https://unpkg.com/vue/dist/vue.js"></script>
        <script src="./js/checkcode.js"></script>
        <!-- import JavaScript -->
        <script src="https://unpkg.com/element-ui/lib/index.js"></script>
        <!-- jquery -->
        <script src="https://ajax.aspnetcdn.com/ajax/jquery/jquery-3.5.1.min.js"></script>
        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
	</head>
 
	<body>
        <div class="store-login" id="app">
            <div class="login-bg">
                <!-- 登录表单 -->
                <el-form :model="loginForm" ref="LoginFormRef" :rules="loginFormRules" label-width="70px" class="login_form">
                    <!-- 用户名 -->
                    <el-form-item label="用户名" prop="username">
                        <el-input v-model="loginForm.username" prefix-icon="iconfont icon-user"></el-input>
                    </el-form-item>
                    <!-- 密码 -->
                    <el-form-item label="密码" prop="password">
                        <el-input type="password" v-model="loginForm.password" prefix-icon="iconfont icon-3702mima"></el-input>
                    </el-form-item>
                    <el-form-item label="验证码"  prop="code" style="height: 40px;margin-bottom: 20px;">
                     <el-input v-model="loginForm.code" maxlength="8" placeholder="请输入验证码"></el-input>
                     <div class="divIdentifyingCode">
                     <canvas id="canvas" width="100" height="40" onclick="dj()" 
                	  style="border: 1px solid #ccc;
                        border-radius: 5px;" title="若验证码不显示，可点击一下"></canvas>
                    </div >
                     </el-form-item>
                    <!-- 按钮 -->
                    <el-form-item class="btns" label-width="0px">
                        <el-button type="primary" @click="login">登录</el-button>
                        <el-button type="info" @click="register">注册</el-button>
                    </el-form-item>
                </el-form>
            </div>
        </div>
        <script>
        //////////////////////////////////
        new Vue({
            el: '#app',
            data: function() {
                var validcode = (rule, value, callback) => {
                          var num = show_num.join("");
                    	  if (value === '') {
                             callback(new Error('请输入验证码!'))
                             // password 是表单上绑定的字段
                          } else if (value !== num) {
                             callback(new Error('验证码输入错误!'))
                          } else {
                           	 callback()
                          }
                        };
                return {
                    //数据绑定
                    show: {
                        diplay: 'blok',
                    },
                    loginForm: {
                        username: '',
                        password: '',
                        code: '',
                    },
                    //表单验证规则
                    loginFormRules: {
                        username: [{
                                required: true,
                                message: '请输入登录名',
                                trigger: 'blur'
                            },
                            {
                                min: 2,
                                max: 10,
                                message: '登录名长度在 2 到 10 个字符',
                                trigger: 'blur'
                            }
                        ],
                        password: [{
                                required: true,
                                message: '请输入密码',
                                trigger: 'blur'
                            },
                            {
                                min: 6,
                                max: 15,
                                message: '密码长度在 6 到 15 个字符',
                                trigger: 'blur'
                            }
                        ],
                        code: [{
                                required: true,
                                validator: validcode,
                                trigger: 'blur'
                            }
                        ]
                    }
                }
            },
            methods: {
                login: function (){
                    //点击登录的时候先调用validate方法验证表单内容是否有误
                    this.$refs.LoginFormRef.validate(async valid => {
                        console.log(this.loginFormRules)
                        //如果valid参数为true则验证通过
                        if (!valid) {
                            console.log("error");
                            return
                        }
                        //document.cookie = 'username' + "=" +this.loginForm.username; 
                        console.log("hello");
                        console.log(this.loginForm.code);
                        console.log(show_num.join(""));
                        var params = new URLSearchParams();
                        params.append('username',this.loginForm.username);
                        params.append('password',this.loginForm.password);
                        //console.log(params.has("username"));
                        
                        axios.post('./login.php', params).then(res => 
                        {console.log(res.data)
                        if(res.data==1 || res.data==2)
                        {
                            console.log("登录失败");
                            window.location.href="/finalsys_user/fail.html"; 
                        }
                        else if(res.data==3)
                        {
                            console.log("登录成功");
                            window.location.href="/finalsys_user/choose.php";
                        }
                        });
                    })
                    
                },
                register:function(){
                     window.location.href="register.html"; 
                },
                changBg() {
                    // alert(11);
                    // const bglogin = document.getElementsByClassName("bg-login");
                    // console.log(bglogin);
                   
                    $(".bg-login>li:eq(" + this.index + ")").fadeIn("3000").siblings().fadeOut("3000");
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
