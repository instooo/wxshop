// pages/authorize/authorize.js
const app = getApp()
Page({

  /**
   * 页面的初始数据
   */
  data: {
   
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {
  
  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
  
  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {
  
  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {
  
  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {
  
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {
  
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {
  
  },
  getUserInfo: function (e) {
    var that = this;
    if (e.detail.encryptedData !=null){//只有在确定授权的情况下，进行跳转
      wx.login({
        success: function (res) {
          var postData = {
            encryptedData: e.detail.encryptedData,
            iv: e.detail.iv,
            code: res.code,
          };
          app.ajax({
            url: app.globalData.serviceUrl + '/user/getwxToken',
            data: postData,
            method: 'POST',
            successCallback: function (res3) {
              console.log(res3);
              if (res3.code == 1) {
                app.globalData.userInfo = res3.userinfo,
                  app.globalData.token = res3.userinfo.token
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
      wx.switchTab({
        url: '../index/index',
        fail: function () {
          console.info("跳转失败")
        }
      })
    }

  }
})