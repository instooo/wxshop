//index.js
//获取应用实例
const app = getApp()

Page({
  data: {
    gdjg: 1000,
    dcbanner: [{
      srcdz: '../../images/banner.png'
    }, {
      srcdz: '../../images/banner.png'
    }, {
      srcdz: '../../`	/banner.png'
    }],
    rmyx:[{
      logo:'../../images/yx-logo.png',
      yxm:'神器萌',
      sl:38
    }, {
        logo: '../../images/yx-logo.png',
        yxm: '神器萌',
        sl: 38
      }, {
        logo: '../../images/yx-logo.png',
        yxm: '神器萌',
        sl: 38
      }, {
        logo: '../../images/yx-logo.png',
        yxm: '神器萌',
        sl: 38
      }],
    zxsx: [{
      logo: '../../images/yx-logo.png',
      yxm: '神器萌',
      sl: 38,
      yxsm:'【联盟天梯 最强之名】'
    }, {
      logo: '../../images/yx-logo.png',
      yxm: '神器萌',
      sl: 38,
        yxsm: '【联盟天梯 最强之名】',
        bq: ['../../images/juan.png', '../../images/juan.png']
    }, {
      logo: '../../images/yx-logo.png',
      yxm: '神器萌',
      sl: 38,
        yxsm: '【联盟天梯 最强之名】'
    }, {
      logo: '../../images/yx-logo.png',
      yxm: '神器萌',
      sl: 38,
        yxsm: '【联盟天梯 最强之名】'
    }]
  },
  //事件处理函数
  bindViewTap: function() {
    wx.navigateTo({
      url: '../logs/logs'
    })
  },
  onLoad: function() {
    if (app.globalData.userInfo) {
      this.setData({
        userInfo: app.globalData.userInfo,
        hasUserInfo: true
      })
    } else if (this.data.canIUse) {
      // 由于 getUserInfo 是网络请求，可能会在 Page.onLoad 之后才返回
      // 所以此处加入 callback 以防止这种情况
      app.userInfoReadyCallback = res => {
        this.setData({
          userInfo: res.userInfo,
          hasUserInfo: true
        })
      }
    } else {
      // 在没有 open-type=getUserInfo 版本的兼容处理
      wx.getUserInfo({
        success: res => {
          app.globalData.userInfo = res.userInfo
          this.setData({
            userInfo: res.userInfo,
            hasUserInfo: true
          })
        }
      })
    }
  },
  getUserInfo: function(e) {
    console.log(e)
    app.globalData.userInfo = e.detail.userInfo
    this.setData({
      userInfo: e.detail.userInfo,
      hasUserInfo: true
    })
  }
})