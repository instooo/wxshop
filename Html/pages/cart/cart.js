var app = getApp();
 
Page({

  /**
   * 页面的初始数据
   */
  data: {
    modalSpecShow: false,
    imageRootPath:'',
    cartList: [],
    selectAllStatus:true,  //默认全选
    updId:'',  //修改id
    updName:'',  //修改的商品
    updSizeName:'',  //修改的颜色
    updNums:0,  //修改的数量
    totalMoney:0,   //总金额
    isEmpty: '',   //是否显示空
    isShow: 'show', //是否显示数据 
    curComment:1,    
    specSmprice: 0,
    specPrice: 0,
    specRentCost: 0,
  },
  onShow: function () {
    //获取数据
    this.getCartInfo();
  },

  /**
   * 生命周期函数--监听页面显示
   */
  onLoad: function (options) {
  },

  //获取列表数据
  getCartInfo:function(){
    var self = this;
    var postData = {
      token: app.globalData.token,
    };
    app.ajax({
      url: app.globalData.serviceUrl + 'rent/rent_list',
      data: postData,
      method: 'GET',
      successCallback: function (res) {         
        if (res.code == 0) { 
          for (var i = 0; i < res.data.rent_list.length; i++) {
            res.data.rent_list[i].selected=true              
          }        
          self.setData({
            imageRootPath: res.data.rooturl,
            cartList: res.data.rent_list,
            selectAllStatus:true,
            isShow: res.data.flag? '': 'hide',
            isEmpty: res.data.flag ? '': 'show', 
          });
          self.countTotalMoney();  //计算总金额
        }
      },
      failCallback: function (res) {
        console.log(res);
      }
    });
  },

  //计算总金额
  countTotalMoney:function(){
    var cartList = this.data.cartList;
    var money=0;
    for (var i = 0; i < cartList.length; i++) {
      if (cartList[i].selected) {
        money = money+cartList[i].price*cartList[i].num
      }
    }
    this.setData({
      totalMoney: money
    });
  },  
  //单选框事件
  selectList:function(e){
    var index = e.currentTarget.dataset.index;    // 获取data- 传进来的index
    var cartList = this.data.cartList;                    // 获取购物车列表
    var selected = cartList[index].selected;         // 获取当前商品的选中状态
    cartList[index].selected = !selected;              // 改变状态
    this.setData({
      cartList: cartList
    });

    //判断是否全选
    var selectAllStatus=true;
    for (var i = 0; i < cartList.length;i++){
      if (!cartList[i].selected){
        selectAllStatus=false;
        break;
      }
    }
    this.setData({
      selectAllStatus: selectAllStatus
    });
    this.countTotalMoney();  //计算总金额
  },

  //全选事件
  selectAll:function(e){
    var selectAllStatus = this.data.selectAllStatus;    // 是否全选状态
    selectAllStatus = !selectAllStatus;
    var cartList = this.data.cartList;

    for (var i = 0; i < cartList.length; i++) {
      cartList[i].selected = selectAllStatus;            // 改变所有商品状态
    }
    this.setData({
      selectAllStatus: selectAllStatus,
      cartList: cartList
    });
    this.countTotalMoney();  //计算总金额
  },
  //数量输入
  bindNumberChange: function (e) {
    this.setData({
      updNums: e.detail.value
    });
  },
  //添加数量
  addNumber: function (e) {
    var index = e.currentTarget.dataset.index;
    var nownum = Number(this.data.cartList[index].num)+1;
    var self = this;
    if (/^[0-9]+$/.test(nownum)) {
      this.data.cartList[index].num = nownum;
      this.setData({
        cartList: this.data.cartList
      });        
      var postData = {
        token: app.globalData.token,
        id: this.data.cartList[index].id,
        num: nownum
      };
      app.ajax({
        url: app.globalData.serviceUrl + 'rent/rent_edit',
        data: postData,
        method: 'GET',
        successCallback: function (res) {
          if (res.code == 0) {
            //计算金额
            self.countTotalMoney();  //计算总金额
          }
        }
      })     
    } else {
      self.showMsg('请输入正确的数量');
      return false;
    }
  },
  //减少数量
  reduceNumber: function (e) {
    var index = e.currentTarget.dataset.index;
    var nownum = Number(this.data.cartList[index].num) - 1;
    var self = this;
    if (/^[0-9]+$/.test(nownum)) {
      nownum = Number(nownum);
      nownum = nownum > 0 ? nownum  : 1;
      this.data.cartList[index].num = nownum;
      this.setData({
        cartList: this.data.cartList
      });
      var postData = {
        token: app.globalData.token,
        id: this.data.cartList[index].id,
        num: nownum
      };
      app.ajax({
        url: app.globalData.serviceUrl + 'rent/rent_edit',
        data: postData,
        method: 'GET',
        successCallback: function (res) {
          if (res.code == 0) {
            //计算金额
            self.countTotalMoney();  //计算总金额
          }
        }
      })
    } else {
      self.showMsg('请输入正确的数量');
      return false;
    }
  },
  //提交删除
  deleteModal:function(e){    
    //获取勾选项
    var self = this;
    wx.showModal({
      title: '提示',
      content: '确定删除？',
      success: function (res) {
        if (res.confirm) {
          var id = e.currentTarget.dataset.index;
          var postData = {
            token: app.globalData.token,
            id: id
          };    
         
          app.ajax({
            url: app.globalData.serviceUrl + 'rent/rent_del',
            data: postData,
            method: 'POST',
            successCallback: function (res) {
              if (res.code == 0) {  
                var cartList = self.data.cartList;     
                var newArr = new Array();          
                for (var i = 0; i < cartList.length; i++) {                
                  if (Number(cartList[i]['id']) != Number(res['data'])){                  
                    newArr.push(cartList[i]);
                  }
                }                 
                self.setData({                
                  cartList: newArr
                });  
                self.countTotalMoney();  //计算总金额            
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

  //进行下单
  gotoConfirm:function(){
    //获取勾选项
    var self=this;    
    var rentids = [];    
    var cartList = self.data.cartList;
    var selNums = 0;
    for (var i = 0; i < cartList.length; i++) {
      if (cartList[i].selected) {
        var selCart = cartList[i];
        rentids.push(selCart.id);      
        selNums = selNums + 1;
      }
    }
    if (selNums == 0) {
      self.showMsg('至少选择一个进行下单');
      return false;
    }
    //跳转到订单提交页面 
    wx.navigateTo({
      url: '/pages/order/confirm/confirm?rentids=' + rentids.join(",")
    })
  },

  showMsg: function (msg) {
    wx.showModal({
      title: '提示',
      content: msg,
      showCancel: false,
      confirmText: '我知道了'
    });
  },

  gotoIndex: function() {
    wx.switchTab({
      url: '/pages/index/index'
    })
  }
})