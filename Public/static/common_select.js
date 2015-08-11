/* 
 * 地区选择函数
 */

function regionInit(divId){
	var area_id = 0;	
	getArea(function(){
		if(typeof(nc_a[area_id]) == 'object' && nc_a[area_id].length > 0){//数组存在
			var area_select = $("#" + divId + " > select");//选择要初始化的对象
			areaInit(area_select,area_id);
		}
		$("#" + divId + " > select").change(regionChange); // select的onchange事件
		$("#" + divId + " > input:button[class='edit_region']").click(regionEdit); // 编辑按钮的onclick事件
	});
}
function areaInit(area_select,area_id){//初始化地区
	getArea(function(){
		if(typeof(area_select) == 'object' && nc_a[area_id].length > 0){
			var areas = new Array();
			areas = nc_a[area_id];
			$(area_select).append("<option>-请选择-</option>");
			for (i = 0; i <areas.length; i++){
				$(area_select).append("<option value='" + areas[i][0] + "'>" + areas[i][1] + "</option>");
			}
		}
	});
}
if(typeof(regionChange) != 'function'){//检测是否已经被定义过，防止重写
	function regionChange(){
	    // 删除后面的select
	    $(this).nextAll("select").remove();
	    // 计算当前选中到id和拼起来的name
	    var selects = $(this).siblings("select").andSelf();
	    var id = '';
	    var id_arr = [];
	    var names = new Array();
	    for (i = 0; i < selects.length; i++){
	        sel = selects[i];
	        if (sel.value > 0){
	            id = sel.value;
	            name = sel.options[sel.selectedIndex].text;
	            names.push(name);
	            id_arr.push(id);
	        }
	    }
	    //$(".area_ids").val(id);
	    if(id_arr[0] >= 32 && id_arr[0] <= 34){//港澳台只有两级
	    	$(".area_ids").val(id);
	    }else{
	    	$(".area_ids").val(id_arr[2]);
	    }
	    $(".area_name").val(name);
	    $(".area_names").val(names.join("\t"));
	    $("#drug_province_id").val(id_arr[0]);
	    $("#drug_city_id").val(id_arr[1]);
	    
	    if (this.value > 0){//下级地区
	        var area_id = this.value;
	        if(typeof(nc_a[area_id]) == 'object' && nc_a[area_id].length > 0){//数组存在
	        	$("<select></select>").change(regionChange).insertAfter(this);
	        	areaInit($(this).next("select"),area_id);//初始化地区
	        }
	    }
	}
}

function regionEdit()
{
    $(this).siblings("select").show();
    $(this).siblings("span").andSelf().hide();
}

if(typeof(jQuery.validator.addMethod) == 'function'){//添加自动检测是否是最后一级地区
	jQuery.validator.addMethod("checkarea", function(value, element) {
		return this.optional(element) || (typeof(nc_a[value]) == 'undefined');//当数组不存在时确定选到最后
	}, "请选择所在地区");
} 
function getArea(callback){
	if(typeof(nc_a) == 'undefined'){//加载地区数据
		var area_scripts_src = '';
		area_scripts_src = $("script[src*='jquery.js']").attr("src");//取JS目录的地址
		area_scripts_src = area_scripts_src.replace('jquery.js', 'area_array.js');
		$.getScript(area_scripts_src,function(){
				callback();
				return true;
		});
	} else {
		callback();
	}
}