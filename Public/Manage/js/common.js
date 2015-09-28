/**
 * 去掉首尾的空格
 * 
 * @param string str 字符串
 * @returns string
 */
function trim(str)
{
	return str.toString().replace(/(^\s*)|(\s*$)/g, ""); 
}

/**
 * 区域select构建对象，及联动选择菜单
 * 
 * @version 2015.9.26
 * @author tangguosheng <tanggsh@163.com>
 */
var areaSelect = function(setting){
	return areaSelect.prototype.init(setting);
};
areaSelect.prototype = {
	initAreaId:0,	//初始化区域id
	initUrl:'',	//区域初始化数据请求地址
	getChildrenUrl:'',	//子区域请求地址
	selectBoxObj:null,	//存放区域select的jQuery对象
	selectArrName:'area',	//区域选择select表单name的值数组
	classNames:'',
	
	
	init:function(setting){
		for(var i in setting){
			this[i] = setting[i];		
		}
		
		//事件注册
		this.registEvent();
		return this;
	},
	
	//注册事件
	registEvent:function()
	{
		//初始化选择框
		this.initSelect();
	},
	
	initSelect:function(){
		var _self = this;
		$.ajax({
            url:_self.initUrl,
            type:'post',
            data:{id:_self.initAreaId},
            dataType:'json',
            async:false,
            success:function(rel){
            	if(rel){
            		for(var list in rel){
            			_self.setSelectObj(rel[list].data, rel[list].selected);
            		}
            	}               
            }//end success function
        });
	},
	//设置select元素
	setSelectObj:function(rel, selectId){
		var htmlStr = '<select name="'+this.selectArrName+'[]" class="'+this.classNames+'"><option value="0">-请选择-</option>'; 
		for(var i in rel){
			var obj = rel[i];
			htmlStr += '<option value="'+obj.id+'" '+(obj.id==selectId ? 'selected="true"':'')+'>'+obj.name+'</option>';
		}
		htmlStr += '</select>';
		var selectObj = $(htmlStr);
		this.selectBoxObj.append(selectObj);
		this.changeEvent(selectObj);
	},
	
	//注册区域选择事件
	changeEvent:function(obj){
		var _self = this;
		obj.change(function(){
			$(this).nextAll('select').remove();
			var areaId = $(this).val();			
			$.ajax({
                url:_self.getChildrenUrl,
                type:'post',
                data:{id:areaId},
                dataType:'json',
                async:false,
                success:function(rel){
                    if(rel){
                        _self.setSelectObj(rel, 0); 
                    }
                }//end success function
            });
		});
	}
};
areaSelect.prototype.init.prototype = areaSelect.prototype;