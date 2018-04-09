// index.js
//获取应用实例
var app = getApp();

Page({
  data: {
    'banner_list': [],
    'imageRootPath': '',
    'good_list':[],
    'indicatorDots': true,
    'indicatorColor': '#bdaea7',
    'indicatorActiveColor': '#5eaaf9',
    'autoplay': true,
    'interval': 5000,
    'duration': 1000,
    'foodList': []
  },
  
  onLoad: function (options) {
    var self = this;
    self.getIndexData();
  },
  getIndexData: function() {
    var self = this;
    var postData = {
      token: ''
    };
    
    //获取首页数据    
    app.ajax({
      url: app.globalData.serviceUrl + 'index.html',
      data: postData,
      method: 'GET',
      successCallback: function(res) {       
        self.setData({
          imgUrls: res.data.poslinklist,
          imageRootPath: res.data.rooturl
        }); 
        self.setData({
          good_list: res.data.good_list,
          banner_list: res.data.banner_list
        });
      },
      failCallback: function(res) {
        console.log(res);
      }
    });

  },

  //tab切换
  productList: function (event) {
    var idx = event.currentTarget.dataset.idx;
    var self=this;
    var allwarelist = self.data.allwarelist;
    self.setData({
      warelist: allwarelist[idx]
    });
  },

  //商品详情
  productInfo: function (event){
    var id = event.currentTarget.dataset.id;
    wx.navigateTo({
      url: '/pages/product/info/info?id=' + id
    })
  },
  
  //更多跳转
  gotoMore: function () {
    wx.switchTab({
      url: '/pages/product/list/list'
    })
  },

  //分享
  onShareAppMessage: function () {
    return {
      title: '母婴',
      desc: '母婴描述!',
      path: '/pages/index/index'
    }
  }

})