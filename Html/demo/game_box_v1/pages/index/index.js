var app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {
    hasUserInfo: false,
    canIUse: wx.canIUse('button.open-type.getUserInfo'),
    loading: true,
    noData: false,
    imageRootPath: '',
    typeid: 1,//首页轮播图
    hot_tag:'hot',
    new_tag: 'new',
    page:0,
    banner_list: [],
    game_hot_list: [],
    game_new_list:[]
  },
  /**
   * 生命周期函数--监听页面显示
   */
  onLoad: function (options) {
    var that = this;
    that.get_banner_list();
    that.get_hot_list();
    that.get_new_list();
  },
  get_banner_list: function () {
    //if (!app.globalData.token) {//用户为登录，跳转到登录页面
    //  wx.redirectTo({ url: "/pages/member/index" });    
    //  return false;
    //}
    var that = this;
    var postData = {
      token: app.globalData.token,
      typeid: that.data.typeid
    };
    //获取首页数据    
    app.ajax({
      url: app.globalData.serviceUrl + '/Banner/banner_list',
      data: postData,
      method: 'POST',
      successCallback: function (res) {
        var list = [];
        if (res.code == 1) {
          list = res.data;
        }else{
          console.log(res);
        }
        that.setData({
          imageRootPath: app.globalData.serviceUrl,
          banner_list: list
        });
      },
      failCallback: function (res) {
        console.log(res);
      }
    });
  },
  get_hot_list: function () {
    var that = this;
    var postData = {
      token: app.globalData.token,
      tags: that.data.hot_tag
    };
    //获取首页数据    
    app.ajax({
      url: app.globalData.serviceUrl + '/Game/game_list',
      data: postData,
      method: 'POST',
      successCallback: function (res) {
        var hot_list = [];       
        if (res.code == 1) {
          hot_list = res.data;
        } else {
          console.log(res);
        }
        that.setData({
          imageRootPath: app.globalData.serviceUrl,
          game_hot_list: hot_list,         
        });
      },
      failCallback: function (res) {
        console.log(res);
      }
    })    
  },
  get_new_list: function () {
    var that = this;
    var postData = {
      token: app.globalData.token,
      tags: that.data.new_tag,
      page: that.data.page
    };
    that.data.loading = false;
    //获取首页数据    
    app.ajax({
      url: app.globalData.serviceUrl + '/Game/game_list',
      data: postData,
      method: 'POST',
      successCallback: function (res) {       
        var new_list = [];
        if (res.code == 1) {        
          new_list = res.data;
          if (res.data == null) {            
            that.setData({
              noData: true,  //显示已经没有数据
              loading: false  //滚动不用再触发
            });
          }else{
            that.setData({            
              loading: false  //滚动不用再触发
            });
          }
        } else {
          console.log(res);
        }
        that.setData({
          imageRootPath: app.globalData.serviceUrl,
          game_new_list: new_list
        });
      },
      failCallback: function (res) {
        console.log(res);
      }
    })
  },
  /**
 * 页面上拉触底事件的处理函数
 */
  onReachBottom: function () {  
    var that =this;
    //可以分页  
    if (that.data.loading) {
      var page = this.data.page + 1;
      that.setData({
        page: page,
        loading: true
      });
      that.get_new_list();
    }
  }
})