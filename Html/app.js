//app.js
App({
  onLaunch: function() {
    //获取token
    this.getToken();
  },
  //获取token
  getToken: function(callback) {
     var self = this;
     wx.login({ 
      success: function (res) { 
        wx.getUserInfo({
          success: function (res2) {             
            var userInfo = res2.userInfo
            var nickName = userInfo.nickName
            var avatarUrl = userInfo.avatarUrl
            var gender = userInfo.gender //性别 0：未知、1：男、2：女
            var province = userInfo.province
            var city = userInfo.city
            var  country = userInfo.country
            var _code = res.code;
            var url = self.globalData.serviceUrl + 'user/mwxgettoken?';
            var data = {
              _code: _code,
              nickname: nickName,
              headimgurl: avatarUrl,
              sex: gender,
              province: province,
              city: city,
              country: country,
            };
                if (callback) {                  
                    callback();
                }
          //self.ajax({
          //  url: url,
          //  data: data,
          //  method: 'GET',
          //  successCallback: function (res) {
          //    if (res.code == 0) {
          //      self.globalData.token = res.data;
          //      if (callback) {
          //          callback();
          //      }
          //    }
          //  },
          //  failCallback: function (res) {
          //    //console.log(res);
          //  }
          //});

        }          
        }); 
      },      
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
        fail: function(res) {
          obj.failCallback && obj.failCallback(res);
        }
    });
  },

  getUserInfo: function(cb) { 
    var that = this;
    if (this.globalData.userInfo) {
      typeof cb == "function" && cb(this.globalData.userInfo)
    } else {
      //调用登录接口
      wx.getUserInfo({
        withCredentials: true,
        success: function(res) {
          that.globalData.userInfo = res.userInfo
          typeof cb == "function" && cb(that.globalData.userInfo)
        }
      })
    }
  },

  globalData: {
    orderTab:'sy',
    token:'',
    userInfo: null,
    //serviceUrl: 'https://www.instooo.com/',
    serviceUrl: 'http://api.wxshop.me/'
  }
})
