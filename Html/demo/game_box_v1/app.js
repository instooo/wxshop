//app.js
App({
  onLaunch: function () {    
    //获取token
    this.getSetting(); 
    this.getToken();
    
  },
  //获取token
  getToken: function () {     
    var that = this;
    wx.login({ 
      success: function (res) {        
        if (that.globalData.hasAuth){
          wx.getUserInfo({
            success: function (res2) {                 
              var postData = {
                encryptedData: res2.encryptedData,
                iv: res2.iv,
                code: res.code,    
              };               
              that.ajax({
                url: that.globalData.serviceUrl + '/user/getwxToken',
                data: postData,
                method: 'POST',
                successCallback: function (res3) {    
                  console.log(res3);             
                  if (res3.code == 1) {
                      that.globalData.userInfo = res3.userinfo,
                      that.globalData.token = res3.userinfo.token
                  } else {
                    console.log(res3);
                  }                 
                },
                failCallback: function (res) {
                  console.log(222);
                }            
              });
            }
          });
        }else{
          console.log(333);
        }      
      
      }
    });
  },
  ajax: function (obj) {
    wx.request({
      url: obj.url,
      data: obj.data,
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: obj.method || 'POST',
      success: function (res) {
        obj.successCallback && obj.successCallback(res.data);
      },
      fail: function (res) {
        obj.failCallback && obj.failCallback(res);
      }
    });
  },
  //获得基础信息
  getSetting:function(){
    var that = this;
    wx.getSetting({
      success: function (res) {         
        if (res.authSetting['scope.userInfo']) {//判断是否获得授权 
          that.globalData.hasAuth=true
        }else{
          wx.navigateTo({ url: "/pages/authorize/authorize" });
        }
       },     
    })
  },  
  
  globalData: {  
    token: '',
    userInfo: null,
    serviceUrl: 'https://gameapi.weixin.7477.com',   
  }, 
})
