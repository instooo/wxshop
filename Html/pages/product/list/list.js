//index.js
//获取应用实例 
var app = getApp();

Page({
  data: {
    'imageRootPath': '',
    'goodlist': [],
    'typelist': [],
    'page':1,
    'loading': true,
    'noData': false,
    'isFirst': true,
    'typeid': 0,
    'hide': 'hide',    
  },

  /**
   * 生命周期函数--监听页面显示
   */
  onLoad: function (options) {
    this.setData({
      'goodlist': [],
      'typelist': [],
      'page': 1,
      'loading': true,
      'noData': false,
      'isFirst': true,
      'typeid': 0,
      'hide': 'hide'
    });
    var self=this;
    if (!app.globalData.token) {
      app.getToken(function () {
        self.getIndexData();
      });
    } else {
      self.getIndexData();
    }
  },

  onShow: function() {
  },

  getIndexData: function() {
    var self = this;
    var postData = {
      typeid: self.data.typeid,      
      page: self.data.page
    };

    self.data.loading = false;
    //获取首页数据    
    app.ajax({
      url: app.globalData.serviceUrl + '/index/goodslist',
      data: postData,
      method: 'GET',
      successCallback: function(res) {      
        var list = [];
        if (res.code != 0 || res.data == null || res.data.good_list.length < 1) {
          self.setData({
            noData: true,  //显示已经没有数据
            loading: false  //滚动不用再触发
          });
          return false;
        }

        if (self.data.isFirst && res.data.type_list && res.data.type_list.length > 0 ) {
          self.setData({
            typelist: res.data.type_list
          });
          self.data.isFirst == false;
        }
        self.setData({
          imageRootPath: res.data.rooturl,
          goodlist: res.data.good_list,
          hide: 'hide',
          loading:false
        });
      },
      failCallback: function(res) {
        console.log(res);
      }
    });
  },

  //滚动分页
  bindDownLoad: function () {
    //可以分页
    if (this.data.loading) {
      var page = this.data.page + 1;
      this.setData({
        page: page,
        loading: true
      });
      this.getIndexData();
    }
  },

  //商品详情
  productInfo: function (event) {
    var id = event.currentTarget.dataset.id;
    wx.navigateTo({
      url: '/pages/product/info/info?id=' + id
    })
  },

  //获取分类数据
  getCategoryInfo: function(event) {
    var id = event.currentTarget.dataset.id;    
    var self = this;
    self.setData({
      page: 1,
      loading: true,
      noData: false,
      goodlist: [],
      typeid: id,
      hide: 'hide'
    });

    self.getIndexData();
  },
  //分享
  onShareAppMessage: function () {
    return {
      title: '母婴',
      desc: '母婴描述!',
      path: '/pages/product/list/list'
    }
  }

})