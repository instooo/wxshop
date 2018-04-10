var app = getApp();

Page({
  data: {
    myInfo: null
  },

  onLoad: function (options) {
    // if (!app.globalData.token) {
    //   wx.navigateTo({ url: "/pages/login/login" });
    //   return false;
    // } 
    this.getMyData();
  },

  getMyData: function() {
    var self = this;
    app.ajax({
      url: app.globalData.serviceUrl + 'user/muserinfo',
      data: { token: app.globalData.token},
      method: 'POST',
      successCallback: function(res) {      
        if(res.code == 0) {
          self.setData({
            myInfo: res.data.info
          });
        }
      },
      failCallback: function(res) {
      }
    });
  },

  //积分明细
  gotoScore:function(){
    wx.navigateTo({ url: "/pages/scroce/scroce"});
  },

  //我的订单
  gotoMyOrder:function(){
    wx.navigateTo({ url: "/pages/order/list/list" });
  },

  //我的地址
  gotoMyAddress:function(){
    wx.navigateTo({ url: "/pages/address/list/list" });
  },
  //常见问题
  gotoQuestion:function() {
    wx.navigateTo({ url: "/pages/question/list/list" });
  },

  //常见问题
  gotoService:function() {
    wx.navigateTo({ url: "/pages/service/service" });
  },
  //我的收藏
  gotoCollection:function(){
    wx.navigateTo({ url: "/pages/collection/collection" });
  }

});