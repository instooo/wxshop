var app = getApp();
var WxParse = require('../../../wxParse/wxParse.js');

Page({

  /**
   * 页面的初始数据
   */
  data: {
    modalSpecShow:false,//弹出窗隐藏
    id: 0, //产品id
    goodinfo: null, //产品详情 
    imageRootPath:'',
    goodsizelist:[],  //规格
    imgUrls:[],         //产品图片地址
    goods_size_id: '',  //购买规格id
    numbers:1,  //购买数量  
    isCart:0,   //默认直接下单
    cartNums:0, //购物车数量默认为0
    curComment: 'proInfo',//默认tab展示位置
    price:0,
    showprice:0,
    description:""   
  },

  /**
   * 生命周期函数--监听页面显示
   */
  onLoad: function (options) {   
    this.setData({
      id: options.id
    });
  },

  onShow: function() {   
    var self = this;
    if (!app.globalData.token) {     
      app.getToken(function(){
        self.getInfoData();
      });
    } else {      
      self.getInfoData();
    }
  },

  //获取产品详情信息
  getInfoData: function() {    
    var self = this;
    var postData = {
      id: self.data.id,
      token: app.globalData.token
    }; 
    app.ajax({
      url: app.globalData.serviceUrl + 'index/goodsDetail',
      data: postData,
      method: 'GET',
      successCallback: function(res) {   
        if(res.code == 0 && res.data!=null){         
          self.setData({
            imageRootPath: res.data.rooturl,
            goodinfo: res.data.goodsinfo,
            goodsizelist: res.data.goodssize,
            price: res.data.goodsinfo.price,            
            imgUrls: res.data.goodsinfo.thumblist,
            cartNums:res.data.rentcount
          });
          if (res.data.goodssize != null) {
            self.setData({             
              goods_size_id: res.data.goodssize[0].id,
              showprice: res.data.goodssize[0].price
            });
          }
          /** 
            * WxParse.wxParse(bindName , type, data,target,imagePadding) 
            * 1.bindName绑定的数据名(必填) 
            * 2.type可以为html或者md(必填) 
            * 3.data为传入的具体数据(必填) 
            * 4.target为Page对象,一般为this(必填) 
            * 5.imagePadding为当图片自适应是左右的单一padding(默认为0,可选) 
            */
          var description = res.data.goodsinfo.description;
          if (description != null && description!=''){
            WxParse.wxParse('description', 'html', description, self, 5);
          }
        }
      },
      failCallback: function(res) {
        console.log(res);
      }
    });
  },  
  //tab
  commentFun: function (e) {
    var self = this;
    var curComment = e.currentTarget.dataset.status;
    this.setData({
      curComment: curComment
    });
  },
  //弹框选择规格
  openModal: function () {
    this.setData({
      modalSpecShow: true,
      isCart: 0,
      numbers: 1     
    });
  },
  //加入购物车弹框
  addCartModal: function () {
    this.setData({
      modalSpecShow: true,
      isCart: 1,
      numbers: 1     
    });
  },
  //关闭弹框
  closeModal: function () {
    this.setData({
      modalSpecShow: false,
      isCart: 0, 
      numbers: 1
    });
  },
  //数量输入
  bindNumberChange: function (e) {
    this.setData({
      numbers: e.detail.value
    });
  },
  //添加数量
  addNumber: function () {
    var self = this;
    var numbers = self.data.numbers;    
    if (/^[0-9]+$/.test(numbers)) {
      numbers = Number(numbers);    
      numbers = numbers + 1;     
      self.setData({
        numbers: numbers,       
      });
    } else {
      self.showMsg('请输入正确的数量');
      return false;
    }
  },
  //减少数量
  reduceNumber: function () {
    var self = this;
    var numbers = self.data.numbers;
    if (/^[0-9]+$/.test(numbers)) {
      numbers = Number(numbers);
      numbers = numbers > 1 ? numbers - 1 : 1;
      self.setData({
        numbers: numbers
      });
    } else {
      self.showMsg('请输入正确的数量');
      return false;
    }
  },

  //提交订单
  confirmModal: function (e, modalName) {
    var self=this;
    //数量
    var numbers = self.data.numbers;
    if (/^[0-9]+$/.test(numbers) && numbers != 0) {
      numbers = Number(numbers)
    } else {
      self.showMsg('请输入正确的数量');
      return false;
    }    
    var goods_id = self.data.id;  //商品id
    var goods_size_id = self.data.goods_size_id;  //规格id
    self.setData({
      modalSpecShow: false
    });   
    if (self.data.isCart == 1) { //加入购物车     
      var postData = {
        token: app.globalData.token,
        goods_id: goods_id,       
        goods_size_id: goods_size_id,
        num: numbers,       
      };
      app.ajax({
        url: app.globalData.serviceUrl + 'rent/add_rent',
        data: postData,
        method: 'GET',
        successCallback: function (res) {
          if (res.code == 0) {
            var cartNums = Number(self.data.cartNums)+1;
            self.setData({
              cartNums: cartNums
            });
          }
        }
      })
    } else {//跳转到订单提交页面       
      wx.redirectTo({
        url: '/pages/order/confirm/confirm?goodid=' + goods_id + '&numbers=' + numbers + 
        '&goods_size_id=' + goods_size_id
      })
    }
  },

  //跳转到购物车
  gotoCart:function(e){
    wx.switchTab({
      url: '/pages/cart/cart'
    })
  },

  //选择规则
  selectSpec: function (e) {
    var data = e.target.dataset;
    this.setData({
      specKucun: data.kuncun,
      specPrice: data.price,
      specRentCost: data.rent_cost,
      goods_size_id: data.id
    });
  }, 
  showMsg: function (msg) {
    wx.showModal({
      title: '提示',
      content: msg,
      showCancel: false,
      confirmText: '我知道了'
    });
  },
  selectType:function(e){
    var data = e.target.dataset;
    this.setData({
      goods_size_id: data.index,
      showprice: data.price
    });
  },
  //分享
  onShareAppMessage: function () {
    var id = this.data.id;
    return {
      title: this.data.goodinfo.name,
      desc: '母婴描述!',
      path: '/pages/product/info/info?id=' + id
    }
  },
  //收藏商品
  scProduct:function(){
    var self=this;
    var good_id = self.data.id;  //商品id
    var good_size_id = self.data.goods_size_id;  //规格id
    var postData = {
      token: app.globalData.token,
      good_id: good_id,
      good_size_id: good_size_id
    };
    app.ajax({
      url: app.globalData.serviceUrl + 'mcollectsub.htm',
      data: postData,
      method: 'GET',
      successCallback: function (res) {
        if (res.code == 0) {
          self.setData({
            scTag: 1,
            scId: res.data
          });
          self.showMsg('收藏成功');
        } else {
          self.showMsg(res.msg);
        }
      }
    })
  },

  //取消收藏
  qxProduct:function(){
    var self = this;
    var cid = self.data.scId;  //收藏id
    var postData = {
      token: app.globalData.token,
      cid: cid
    };
    app.ajax({
      url: app.globalData.serviceUrl + 'mcollectdel.htm',
      data: postData,
      method: 'GET',
      successCallback: function (res) {
        if (res.code==0){
          self.setData({
            scTag: 0,
            scId: 0
          });
          self.showMsg('取消成功');
        }else{
          self.showMsg(res.msg);
        }
      }
    })
  }
})