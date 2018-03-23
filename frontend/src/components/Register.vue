<template>
  <div class="register">
    <h1>{{ msg }}</h1>
    <br /><br />
    <mt-field label="账号" state="success" placeholder="请输入手机号" type="tel" v-model="username"></mt-field>
    <mt-field label="密码" placeholder="请输入密码" type="password" v-model="password"></mt-field>
    <mt-field label="验证码" v-model="captcha">
      <mt-button :disabled="get_captcha_status" plain size="small" @click.native="getCaptcha">{{get_captcha}}</mt-button>
    </mt-field>
    <br /><br />
    <mt-button type="primary" size="large" @click.native="register">注册</mt-button><br />
    <mt-button type="default" size="large" @click.native="login">登录</mt-button>
    <p class="footer">© Ss-System Wong&Young</p>
  </div>
</template>

<script>
export default {
  name: 'Register',
  data () {
    return {
      msg: 'Ss-System',
      username: '',
      password: '',
      captcha: '',
      get_captcha: '获取验证码',
      get_captcha_status: false
    }
  },
  methods: {
    login: function () {
      this.$router.push({ path: 'login' })
    },
    getCaptcha () {
      let that = this
      this.get_captcha_status = true
      let wait = 60
      let loop = setInterval(function () {
        wait--
        if (wait < 0 || wait === 0) {
          clearInterval(loop)
          that.get_captcha_status = false
          that.get_captcha = '获取验证码'
        } else {
          that.get_captcha = wait + '秒后重新获取'
        }
      }, 1000)
      console.log('hh')
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>
h1, h2 {
  font-weight: normal;
}
.mint-cell-wrapper{
  background-origin: border-box;
  background-image:-webkit-gradient(linear, left bottom, left top, from(#dbdbdb), color-stop(100%, #dbdbdb), color-stop(100%, #0000006b));
  background-image: linear-gradient(0deg, #dbdbdb, #dbdbdb 100%, #0000006b 100%);
  background-position: bottom left;
}
.mint-field .mint-cell-title{
  width: auto!important;
  margin-right: 20px;
}
.footer{
  position: fixed;
  bottom: 10px;
  left: -50%;
  right: -50%;
}
</style>
