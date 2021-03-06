var app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {
    imageRootPath: '',
    order:null,

    payTxt: '去支付',  //控制支付状态
    payIng: false,

    modalWlShow: false,
    wlorderno: '',
    wlnoArr: []
  },

  /**
   * 生命周期函数--监听页面显示
   */
  onLoad: function (options) {
    var self = this;
    if (!app.globalData.token) {
      app.getToken(function(){
        self.getOrderDetail(options);
      });
    } else {
      self.getOrderDetail(options);
    }
  },

  getOrderDetail: function(options) {
    var self = this;
    var id = options.id;    
    var postData = {
      token: app.globalData.token,
      id: id
    };
    app.ajax({
      url: app.globalData.serviceUrl + 'order/order_detail',
      data: postData,
      method: 'GET',
      successCallback: function (res) {
        if (res.code == 0 && res.data.orderinfo!=null){
          self.setData({
            order: res.data.orderinfo,
            imageRootPath: res.data.rooturl
          });
        }
      },
      failCallback: function (res) {
        console.log(res);
      }
    });
  },
  //提示支付中，锁定支付状态
  lockPayFun: function () {
    var self = this;
    self.setData({
      payTxt: '支付中...',  //控制支付状态
      payIng: true
    });
  },

  //提示去支付，解锁支付状态
  unlockPayFun: function () {
    var self = this;
    self.setData({
      payTxt: '去支付',  //控制支付状态
      payIng: false
    });
  },

  //订单支付
  payOrder:function(e){
    var id = e.currentTarget.dataset.id;
    var self = this;
    //预防多次点击支付
    if (!self.data.payIng) {
      self.lockPayFun();  //提示支付中，锁定支付状态
      var postData = {
        token: app.globalData.token,
        id: id
      }
      var url = app.globalData.serviceUrl + 'Pay/wxpay'
      wx.showLoading({ title: '正在请求支付', mask: true })
      app.ajax({
        url,
        data: postData,
        method: 'GET',
        successCallback: function (res) {
          wx.hideLoading()
          
          var timeStamp = res.timeStamp;
          var nonceStr = res.nonceStr;
          var pkg = res.package;
          var signType = 'MD5';
          var paySign = res.paySign;

          wx.requestPayment({
            'timeStamp': timeStamp,
            'nonceStr': nonceStr,
            'package': pkg,
            'signType': 'MD5',
            'paySign': paySign,
            'success':function(res){
                success({ code: 0 });
            },
            'fail':function(res){
              self.unlockPayFun();  //提示去支付，解锁支付状态
              self.showMsg('支付未完成，请重新支付！')
            }
          });

          function success(res) {
            const { code, data, msg } = res
            if (code == 0) {
              wx.showToast({
                title: '支付成功',
                icon: 'success'
              })
              setTimeout(function () {
                wx.redirectTo({
                  url: '/pages/order/detail/detail?id=' + id
                })
              }, 1500);
            } else {
              self.unlockPayFun();  //提示去支付，解锁支付状态
              console.error(msg)
            }
          }
        },
        failCallback: function (res) {
          self.unlockPayFun();  //提示去支付，解锁支付状态
          console.log(res);
        }
      })
    }
  },

  //取消订单
  cancelOrder: function (e) {
    var id = e.currentTarget.dataset.id;
    var self = this;
    wx.showModal({
      title: '提示',
      content: '确定取消该订单？',
      success: function (res) {
        if (res.confirm) {
          var postData = {
            token: app.globalData.token,
            id: id
          };
          app.ajax({
            url: app.globalData.serviceUrl + 'order/order_cancel',
            data: postData,
            method: 'POST',
            successCallback: function (res) {
              if (res.code == 0) {
                var order = self.data.order;
                order.status = 7;
                self.setData({
                  order: order
                });
              }
            },
            failCallback: function (res) {
              console.log(res);
            }
          });
        }
      }
    })
  },

  //确认收货
  shOrder: function (e) {
    var id = e.currentTarget.dataset.id;
    var self = this;
    var postData = {
      token: app.globalData.token,
      id: id
    };
    app.ajax({
      url: app.globalData.serviceUrl + 'order/order_shouhuo',
      data: postData,
      method: 'POST',
      successCallback: function (res) {
        self.showMsg(res.msg);
        if (res.code == 0) {
          var order = self.data.order;
          order.status = 4;
          self.setData({
            order: order
          });
        }
      },
      failCallback: function (res) {
        console.log(res);
      }
    });
  },
  
  //商品详情
  productInfo: function (event) {
    var id = event.currentTarget.dataset.id;
    wx.navigateTo({
      url: '/pages/product/info/info?id=' + id
    })
  },

  //返回首页
  gotoIndex: function () {
    wx.navigateBack();   
  },

  showMsg: function (msg) {
    wx.showModal({
      title: '提示',
      content: msg,
      showCancel: false,
      confirmText: '我知道了'
    });
  }
})