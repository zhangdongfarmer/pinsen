/*
	版权所有 2009-2015 荆门泽优软件有限公司
	保留所有权利
	官方网站：http://www.ncmem.com/
	产品首页：http://www.ncmem.com/webplug/http-uploader6/
	产品介绍：http://www.cnblogs.com/xproer/archive/2012/05/29/2523757.html
	开发文档-ASP：http://www.cnblogs.com/xproer/archive/2012/02/17/2355458.html
	开发文档-PHP：http://www.cnblogs.com/xproer/archive/2012/02/17/2355467.html
	开发文档-JSP：http://www.cnblogs.com/xproer/archive/2012/02/17/2355462.html
	开发文档-ASP.NET：http://www.cnblogs.com/xproer/archive/2012/02/17/2355469.html
	升级日志：http://www.cnblogs.com/xproer/archive/2012/02/17/2355449.html
	证书补丁：http://www.ncmem.com/download/WoSignRootUpdate.rar
	VC运行库：http://www.microsoft.com/en-us/download/details.aspx?id=29
	联系信箱：1085617561@qq.com
	联系QQ：1085617561
	更新记录：
		2009-11-05 创建
*/

var HttpUploaderErrorCode = {
	  "0": "连接服务器错误"
	, "1": "发送数据错误"
	, "2": "接收数据错误"
	, "3": "未设置本地文件"
	, "4": "本地文件不存在"
	, "5": "打开本地文件错误"
	, "6": "不能读取本地文件"
	, "7": "公司未授权"
	, "8": "未设置IP"
	, "9": "域名未授权"
	, "10": "文件大小超过限制"//默认为2G
	//md5
	, "200": "无打打开文件"
	, "201": "文件大小为0"
};

var HttpUploaderState = {
	Ready: 0,
	Posting: 1,
	Stop: 2,
	Error: 3,
	GetNewID: 4,
	Complete: 5,
	WaitContinueUpload: 6,
	None: 7,
	Waiting: 8
	,MD5Working:9
};

//删除元素值
Array.prototype.remove = function(val)
{
	for (var i = 0, n = 0; i < this.length; i++)
	{
		if (this[i] != val)
		{
			this[n++] = this[i]
		}
	}
	this.length -= 1
}

function HttpUploaderMgr()
{
	var _this = this;
	this.Config = {
		"EncodeType"		: "GB2312"
		, "Company"			: "荆门泽优软件有限公司"
		, "Version"			: "2,7,100,31354"
		, "License"			: ""//
		, "Authenticate"	: ""//域验证方式：basic,ntlm
		, "AuthName"		: ""//域帐号
		, "AuthPass"        : ""//域密码
        , "CryptoType"      : "md5"//验证方式：md5,sha1,crc
		, "FileFilter"		: "*"//文件类型。所有类型：*。自定义类型：jpg,bmp,png,gif,rar,zip,7z,doc
		, "FileSizeLimit"	: "0"//自定义允许上传的文件大小，以字节为单位。0表示不限制。字节计算工具：http://www.beesky.com/newsite/bit_byte.htm
		, "FilesLimit"		: "0"//文件选择数限制。0表示不限制
		, "AllowMultiSelect": "1"//多选开关。1:开启多选。0:关闭多选
		, "RangeSize"		: "1048576"//文件块大小，以字节为单位。必须为64KB的倍数。推荐大小：1MB。
		, "Debug"			: false//是否打开调式模式。true,false
		, "LogFile"			: "F:\\log.txt"//日志文件路径。需要先打开调试模式。
		, "InitDir"			: ""//初始化路径。示例：D:\\Soft
		, "AppPath"			: ""//网站虚拟目录名称。子文件夹 web
		//文件夹操作相关
		, "UrlFdCreate"		: "http://localhost:81/HttpUploader6/db/fd_create.php"
		, "UrlFdComplete"	: "http://localhost:81/HttpUploader6/db/fd_complete.php"
		, "UrlFdDel"	    : "http://localhost:81/HttpUploader6/db/fd_del.php"
		//文件操作相关
		, "UrlCreate"		: "http://localhost:81/HttpUploader6/db/f_create.php"
		, "UrlPost"			: "http://localhost:81/HttpUploader6/db/f_post.php"
		, "UrlComplete"		: "http://localhost:81/HttpUploader6/db/f_complete.php"
		, "UrlList"			: "http://localhost:81/HttpUploader6/db/f_list.php"
		, "UrlDel"			: "http://localhost:81/HttpUploader6/db/f_del.php"
        //x86
		, "ClsidDroper"		: "0868BADD-C17E-4819-81DE-1D60E5E734A6"
		, "ClsidUploader"	: "3F154995-5C50-4159-9049-524675A101C5"
		, "ClsidPartition"	: "BA0B719E-F4B7-464b-A664-6FC02126B652"
		, "CabPath"			: "http://www.ncmem.com/download/HttpUploader6/HttpUploader6.cab"
		//x64
		, "ClsidDroper64"	: "7B9F1B50-A7B9-4665-A6D1-0406E643A856"
		, "ClsidUploader64"	: "FD5955CA-4003-4CD8-9CB0-9475C81A9BE9"
		, "ClsidPartition64": "307DE0A1-5384-4CD0-8FA8-500F0FFEA388"
		, "CabPath64"		: "http://www.ncmem.com/download/HttpUploader6/HttpUploader64.cab"
		//Firefox
		, "XpiType"		    : "application/npHttpUploader6"
		, "XpiPath"	        : "http://www.ncmem.com/download/HttpUploader6/HttpUploader6.xpi"
		//Chrome
		, "CrxName"			: "npHttpUploader6"
		, "CrxType"		    : "application/npHttpUploader6"
		, "CrxPath"	        : "http://www.ncmem.com/download/HttpUploader6/HttpUploader6.crx"
		, "SetupPath"		: "http://localhost:4955/demoAccess/js/setup.htm"
		, "ExePath"			: "http://www.ncmem.com/download/HttpUploader6/HttpUploader6.exe"
	};

    //biz event
	this.event = {
	      "md5Complete": function (obj, md5) { }
        , "postComplete": function (obj) { }
	}

	this.ActiveX = {
		  "Droper"	    : "Xproer.HttpDroper6"
	    , "Uploader"	: "Xproer.HttpUploader6"
		, "Partition"	: "Xproer.HttpPartition6"
		//64bit
		, "Droper64"    : "Xproer.HttpDroper6x64"
		, "Uploader64"	: "Xproer.HttpUploader6x64"
		, "Partition64"	: "Xproer.HttpPartition6x64"
	};
	
	//附加参数
	this.Fields = {
		 "uname": "test"
		,"upass": "test"
		,"uid":"0"
		,"fid":"0"
	};

	//检查版本 Win32/Win64/Firefox/Chrome
	var browserName = navigator.userAgent.toLowerCase();
	_this.ie = browserName.indexOf("msie") > 0;
	//IE11检查
	_this.ie = _this.ie ? _this.ie : browserName.search(/(msie\s|trident.*rv:)([\w.]+)/)!=-1;
	_this.firefox = browserName.indexOf("firefox") > 0;
	_this.chrome = browserName.indexOf("chrome") > 0;
	
	this.CheckVersion = function()
	{
		//Win64
		if (window.navigator.platform == "Win64")
		{
			_this.Config["CabPath"] = _this.Config["CabPath64"];

			_this.Config["ClsidDroper"] = _this.Config["ClsidDroper64"];
			_this.Config["ClsidUploader"] = _this.Config["ClsidUploader64"];
			_this.Config["ClsidPartition"] = _this.Config["ClsidPartition64"];

			_this.ActiveX["Uploader"] = _this.ActiveX["Uploader64"];
			_this.ActiveX["Partition"] = _this.ActiveX["Partition64"];
		} //Firefox
		else if (this.chrome)
		{
		    _this.Config["XpiPath"] = _this.Config["CrxPath"];
			_this.Config["XpiType"] = _this.Config["CrxType"];
		}
	};
	_this.CheckVersion();
	
	//http://www.ncmem.com/
	_this.Domain = "http://" + document.location.host;

	_this.FileFilter = new Array(); //文件过滤器
	_this.UploaderListCount = 0; 	//上传项总数，只累加
	_this.UploaderList = new Object(); //上传项列表
	_this.UploadQueue = new Array();		//上传队列
	_this.arrIdsErrStop = new Array(); //未上传项ID列表
	_this.arrFiles = new Array(); //正在上传的ID列表
	_this.arrFilesComplete = new Array(); //已上传完的HttpUploader列表
	_this.UploaderPool = new Array();
	_this.UploaderPoolFF = new Array();
	_this.UploaderListDiv = null;//上传列表面板
	_this.ffPart = null;
	_this.iePart = null;
	_this.ieUper = null;
	_this.ieDrop = null;
	_this.tmpFile    = null;
	_this.tmpFolder  = null;
    _this.tmpSpliter = null;

    //加载未上传完的文件列表
    this.loadFiles = function ()
    {
        $.ajax({
            type: "GET"
            //, dataType: 'jsonp'
            //, jsonp: "callback" //自定义的jsonp回调函数名称，默认为jQuery自动生成的随机函数名
            , url: this.Config["UrlList"]
            , data: { uid: this.Fields["uid"], time: new Date().getTime() }
            , success: function (msg)
            {
                var json = eval("(" + decodeURIComponent(msg) + ")");
                $.each(json, function (idx, item)
                {
                	if(item.f_fdTask == "1")//是文件夹任务
                	{
                		_this.ResumeFolder(item);
                	}
                	else
                	{
						if(item.PostComplete == "1")
						{
							_this.addFileCmp(item);
						}
						else
						{
							_this.ResumeFile(item.pathLoc,item.PostedLength,item.PostedPercent,item.FileMD5,item.idSvr,item.pathSvr);
						}
                	}
                });
            }
            , error: function (req, txt, err) { alert("加载文件列表错误！" + req.responseText); }
            , complete: function (req, sta) { req = null; }
        });
    };

	//初始化路径
	this.InitPath = function()
	{
		this.Config["CabPath"] = this.Domain + this.Config["AppPath"] + this.Config["CabPath"];
		this.Config["PostUrl"] = this.Domain + this.Config["AppPath"] + this.Config["PostUrl"];
	};

	//容器的HTML代码
	this.GetHtmlContainer = function()
	{
		var html = '<div class="combinBox">\
						<ul id="cbHeader" class="cbHeader">\
							<li id="liPnlUploader" class="hover">上传新文件</li>\
							<li id="liPnlFiles" >文件列表</li>\
						</ul>\
						<div class="cbBody" id="cbBody">\
							<ul name="cbItem" class="block"><li name="uploadPanel" id="liUploadPanel"></li></ul>\
							<ul name="cbItem" class="cbItem"><li name="listPanel" id="liListerPanel"></li></ul>\
						</div>\
					</div>';
		return html;
	};
	
	//文件上传面板。
	this.GetHtml = function()
	{
		//加载拖拽控件
		var acx = "";
		//acx += '<object id="objFileDroper" classid="clsid:' + this.Config["ClsidDroper"] + '"';
		//acx += ' codebase="' + this.Config["CabPath"] + '" width="192" height="192" >';
		//acx += '</object>';
        //加载npapi控件
	    acx += '<embed name="ffPart" id="objHttpPartitionFF" type="' + _this.Config["XpiType"] + '" pluginspage="' + _this.Config["XpiPath"] + '" width="1" height="1"/>';
		//文件上传控件
		acx += '<object name="ieUper" id="objHttpUpLoader" classid="clsid:' + _this.Config["ClsidUploader"] + '"';
		acx += ' codebase="' + _this.Config["CabPath"] + '#version='+_this.Config["Version"]+'" width="1" height="1" ></object>';
		//文件夹选择控件
		acx += '<object name="iePart" id="objHttpPartition" classid="clsid:' + _this.Config["ClsidPartition"] + '"';
		acx += ' codebase="' + _this.Config["CabPath"] + '#version='+_this.Config["Version"]+'" width="1" height="1" ></object>';
		//acx += '</div>';
		
		//上传列表项模板
		acx += '<div class="UploaderItem" id="tmpFile" name="fileItem">\
					<div class="UploaderItemLeft">\
						<div class="FileInfo">\
							<div name="fileName" class="FileName top-space">HttpUploader程序开发.pdf</div>\
							<div name="fileSize" class="FileSize" child="1">100.23MB</div>\
						</div>\
						<div class="ProcessBorder top-space"><div name="process" class="Process"></div></div>\
						<div name="msg" class="PostInf top-space">已上传:15.3MB 速度:20KB/S 剩余时间:10:02:00</div>\
					</div>\
					<div class="UploaderItemRight">\
						<div class="BtnInfo"><span name="btnCancel" class="Btn">取消</span>&nbsp;<span name="btnDel" class="Btn hide">删除</span></div>\
						<div name="percent" class="ProcessNum">35%</div>\
					</div>';
		acx += '</div>';
		//文件夹模板
		acx += '<div class="UploaderItem" id="tmpFolder" name="folderItem">\
					<div class="UploaderItemLeft">\
						<div class="FileInfo">\
							<span class="folder-pic"></span>\
							<div name="fileName" class="FileName top-space">HttpUploader程序开发.pdf</div>\
							<div name="fileSize" class="FileSize" child="1">100.23MB</div>\
						</div>\
						<div class="ProcessBorder top-space"><div name="process" class="Process"></div></div>\
						<div name="msg" class="PostInf top-space">已上传:15.3MB 速度:20KB/S 剩余时间:10:02:00</div>\
					</div>\
					<div class="UploaderItemRight">\
						<div class="BtnInfo"><span name="btnCancel" class="Btn">取消</span>&nbsp;<span name="btnDel" class="Btn hide">删除</span></div>\
						<div name="percent" class="ProcessNum">35%</div>\
					</div>';
		acx += '</div>';
		//分隔线
		acx += '<div class="Line" id="FilePostLine" name="lineSplite"></div>';
		//上传列表
		acx += '<div id="UploaderPanel" name="itemContainer">\
					<div name="pnlHeader" id="pnlHeader" class="toolbar">\
						<input name="btnAddFiles" id="btnAddFiles" type="button" value="选择多个文件" />\
						<input name="btnAddFolder" id="btnAddFolder" type="button" value="选择文件夹" />\
						<input name="btnPasteFile" id="btnPasteFile" type="button" value="粘贴文件" />\
					</div>\
					<div class="content">\
						<div name="uploadList" id="FilePostLister"></div>\
					</div>\
					<div class="footer">\
						<a href="javascript:void(0)" class="Btn" name="lnkClearComplete" id="lnkClearComplete">清除已完成文件</a>\
					</div>\
				</div>';
		return acx;
	};
	
	//打开上传面板
	this.OpenPnlUpload = function()
	{
		$("#liPnlUploader").click();
	};
	//打开文件列表面板
	this.OpenPnlFiles = function()
	{
		$("#liPnlFiles").click();
	};
	
	//IE浏览器信息管理对象
	this.BrowserIE = {
		"Check": function ()//检查插件是否已安装
		{
			try
			{
				var com = new ActiveXObject(_this.ActiveX["Uploader"]);
				return true;
			}
			catch (e) { return false; }
		}
        , "NeedUpdate": function () {
            return this.GetVersion() != _this.Config["Version"];
        }
		, "GetVersion": function ()
		{
			var obj = this.partition;
			return obj.Version;
		}
        , "GetFileSize": function (path) { return this.partition.GetFileSize(path); }
        , "GetFileLength": function (path)
        {
            return this.partition.GetFileLength(path);
        }
		, "Setup": function ()
		{
			//文件上传控件
			var acx = '<object id="objHttpUpLoader" classid="clsid:' + _this.Config["ClsidUploader"] + '"';
			acx += ' codebase="' + _this.Config["CabPath"] + '" width="1" height="1" ></object>';
			//文件夹选择控件
			acx += '<object id="objHttpPartition" classid="clsid:' + _this.Config["ClsidPartition"] + '"';
			acx += ' codebase="' + _this.Config["CabPath"] + '" width="1" height="1" ></object>';

			$("body").append(acx);
		}
		, "OpenFileDialog": function ()//打开文件选择窗口
		{
			var obj = this.partition;
			obj.FileFilter = _this.Config["FileFilter"];
			obj.AllowMultiSelect = _this.Config["AllowMultiSelect"];
			obj.InitDir = _this.Config["InitDir"];
			if (!obj.ShowDialog()) return;

			var list = obj.GetSelectedFiles();
			if (list == null) return;
			if (list.lbound(1) == null) return;

			for (var index = list.lbound(1); index <= list.ubound(1); index++)
			{
				if (!_this.Exist(list.getItem(index)))
				{
					_this.AddFile(list.getItem(index));
				}
			}
			_this.PostFirst();
		}
		, "OpenFolderDialog": function ()//打开文件夹选择窗口
		{
			var obj = this.partition;
			if (!obj.ShowFolder()) return;

			var json = obj.GetJson();
			_this.AddFolder(json);
			_this.PostFirst();
		}
		, "PasteFiles": function ()//从剪帖板中获取文件
		{
			var obj = this.partition;
			var list = obj.GetClipboardFiles();
			if (list == null) return;
			if (list.lbound(1) == null) return;

			for (var index = list.lbound(1); index <= list.ubound(1); index++)
			{
				if (!_this.Exist(list.getItem(index)))
				{
					_this.AddFile(list.getItem(index));
				}
			}
			_this.PostFirst();
		}
		//参数folder:文件夹路径。示例：D:\\Soft\\，hasChild:是否包含子目录。true,false
		, "GetFiles": function (path, hasChild)//从指目录获取文件列表
		{
			var obj = this.partition;
			var list = obj.GetFiles(path, hasChild);
			if (list == null) return;
			if (list.lbound(1) == null) return;

			for (var index = list.lbound(1); index <= list.ubound(1); index++)
			{
				if (!this.Exist(list.getItem(index)))
				{
					this.AddFile(list.getItem(index));
				}
			}
			_this.PostFirst();
		}
		, "GetMacs": function ()
		{
			var obj = this.partition;
			var list = obj.GetMacs();
			if (list == null) return null;
			if (list.lbound(1) == null) return null;
			var arr = new Array();

			for (var index = list.lbound(1); index <= list.ubound(1); index++)
			{
				arr.push(list.getItem(index));
			}
			return arr;
		}
        , "GetIP": function () { return this.partition.IP; }
        , "GetCpuID": function () { return this.partition.CpuID; }
        , "GetCpuSN": function () { return this.partition.CpuSN; }
        , "GetHardDiskSN": function () { return this.partition.HardDiskSN; }
        , "FormatByte": function (byte) {
            return this.partition.FormatByte(byte);
        }
        , "DropFiles": function () {
            this.partition.DropFiles();
        }
		, "Init": function () { this.partition = _this.iePart; }
        ,"partition":null
	};
	//FireFox浏览器信息管理对象
	this.BrowserFF = {
		"Check": function ()//检查插件是否已安装
		{
		    var mimetype = navigator.mimeTypes;
		    if (typeof mimetype == "object" && mimetype.length)
		    {
		        for (var i = 0; i < mimetype.length; i++)
		        {
		            if (mimetype[i].type == _this.Config["XpiType"].toLowerCase())
		            {
		                return mimetype[i].enabledPlugin;
		            }
		        }
		    }
		    else
		    {
		        mimetype = [_this.Config["XpiType"]];
		    }
		    if (mimetype)
		    {
		        return mimetype.enabledPlugin;
		    }
		    return false;
		}
        , "NeedUpdate": function () {
            return this.GetVersion() != _this.Config["Version"];
        }
		, "GetVersion": function ()
		{
			//var obj = document.getElementById("objHttpPartitionFF");
			return this.partition.GetVersion();
		}
        , "GetFileSize": function (path) { return this.partition.FileSize(path); }
        , "GetFileLength": function (path)
        {
            return this.partition.FileLength(path);
        }
		, "Setup": function ()//安装插件
		{
			var xpi = new Object();
			xpi["Calendar"] = _this.Config["XpiPath"];
			InstallTrigger.install(xpi, function (name, result) { });
		}
		, "OpenFileDialog": function ()//打开文件选择窗口
		{
		    var obj = this.partition;
			obj.FileFilter = _this.Config["FileFilter"];
			obj.FilesLimit = _this.Config["FilesLimit"];
			obj.AllowMultiSelect = _this.Config["AllowMultiSelect"];
			obj.InitDir = _this.Config["InitDir"];

			var files = obj.ShowDialog();
			if (files)
			{
				for (var i = 0, l = files.length; i < l; ++i)
				{
					if (!_this.Exist(files[i]))
					{
						_this.AddFile(files[i]);
					}
				}
				_this.PostFirst();
			}
		}
		, "OpenFolderDialog": function ()//打开文件夹选择窗口
		{
			var list = this.partition.ShowFolder();
			if (list)
			{
			    var json = this.partition.GetJson();
				_this.AddFolder(json);
				_this.PostFirst();
            }
		}
		, "PasteFiles": function ()//从剪帖板中获取文件
		{
			var list = this.partition.GetClipboardFiles();
			if (list)
			{
				for (var i = 0, l = list.length; i < l; ++i)
				{
					if (!_this.Exist(list[i]))
					{
						_this.AddFile(list[i]);
					}
				}
				_this.PostFirst();
			}
		}
		//参数folder:文件夹路径。示例：D:\\Soft\\，hasChild:是否包含子目录。true,false
		, "GetFiles": function (folder, hasChild)//获取指定目录下的所有文件
		{
			var list = this.partition.GetFiles(folder, hasChild);
			if (list)
			{
				for (var i = 0, l = list.length; i < l; ++i)
				{
					if (!_this.Exist(list[i]))
					{
						_this.AddFile(list[i]);
					}
				}
				_this.PostFirst();
			}
		}
		, "GetMacs": function ()
		{
			var list = this.partition.GetMacs();
			return list;
		}
        , "GetIP": function () { return this.partition.GetIP(); }
        , "FormatByte": function (byte)
        {
            return this.partition.FormatByte(byte);
        }
        , "DropFiles": function () {
            this.partition.DropFiles();
        }
		, "Init": function ()//初始化控件
		{
		    this.partition = _this.ffPart;
			var atl = this.partition;
			atl.FileSizeLimit   = _this.Config["FileSizeLimit"];
			atl.RangeSize       = _this.Config["RangeSize"];
			atl.EncodeType      = _this.Config["EncodeType"];
			atl.License         = _this.Config["License"];
			atl.Company         = _this.Config["Company"];
			atl.PostUrl         = _this.Config["UrlPost"];
			atl.Authenticate    = _this.Config["Authenticate"];
			atl.AuthName        = _this.Config["AuthName"];
			atl.AuthPass        = _this.Config["AuthPass"];
			atl.CryptoType      = _this.Config["CryptoType"];
			atl.Debug           = _this.Config["Debug"];
			atl.LogFile         = _this.Config["LogFile"];
			atl.OnPost          = HttpUploader_Process;
			atl.OnStateChanged  = HttpUploader_StateChanged;
		}
	};
	//Chrome浏览器
	this.BrowserChrome = {
		"Check": function ()//检查插件是否已安装
		{
			for (var i = 0, l = navigator.plugins.length; i < l; i++)
			{
				if (navigator.plugins[i].name == _this.Config["CrxName"])
				{
					return true;
				}
			}
			return false;
		}
        , "NeedUpdate": function () {
            return this.GetVersion() != _this.Config["Version"];
        }
		, "GetVersion": function ()
		{
			//var obj = document.getElementById("objHttpPartitionFF");
			return this.partition.GetVersion();
		}
        , "GetFileSize": function (path) { return this.partition.FileSize(path); }
        , "GetFileLength": function (path)
        {
            return this.partition.FileLength(path);
        }
		, "Setup": function ()//安装插件
		{
			document.write('<iframe style="display:none;" src="' + _this.Config["CrxPath"] + '"></iframe>');
		}
		, "OpenFileDialog": function ()//打开文件选择窗口
		{
			var obj = this.partition;
			obj.FileFilter = _this.Config["FileFilter"];
			obj.FilesLimit = _this.Config["FilesLimit"];
			obj.AllowMultiSelect = _this.Config["AllowMultiSelect"];
			obj.InitDir = _this.Config["InitDir"];

			var files = this.partition.ShowDialog();
			if (files)
			{
				for (var i = 0, l = files.length; i < l; ++i)
				{
					if (!_this.Exist(files[i]))
					{
						_this.AddFile(files[i]);
					}
				}
				_this.PostFirst();
			}
		}
		, "OpenFolderDialog": function ()//打开文件夹选择窗口
		{
			var list = this.partition.ShowFolder();
			if (list)
			{
				var json = this.partition.GetJson();
				_this.AddFolder(json);
				_this.PostFirst();
            }
		}
		, "PasteFiles": function ()//从剪帖板中获取文件
		{
			var list = this.partition.GetClipboardFiles();
			if (list)
			{
				for (var i = 0, l = list.length; i < l; ++i)
				{
					if (!_this.Exist(list[i]))
					{
						_this.AddFile(list[i]);
					}
				}
				_this.PostFirst();
			}
		}
		//参数folder:文件夹路径。示例：D:\\Soft\\，hasChild:是否包含子目录。true,false
		, "GetFiles": function (folder, hasChild)//获取指定目录下的所有文件
		{
			var list = this.partition.GetFiles(folder, hasChild);
			if (list)
			{
				for (var i = 0, l = list.length; i < l; ++i)
				{
					if (!_this.Exist(list[i]))
					{
						_this.AddFile(list[i]);
					}
				}
				_this.PostFirst();
			}
		}
		, "GetMacs": function ()
		{
			var list = this.partition.GetMacs();
			return list;
		}
        , "GetIP": function () { return this.partition.GetIP(); }
        , "FormatByte": function (byte)
        {
            return this.partition.FormatByte(byte);
        }
        , "DropFiles": function () {
            this.partition.DropFiles();
        }
		, "Init": function ()//初始化控件
		{
		    this.partition = _this.ffPart;
			var atl = this.partition;
			atl.FileSizeLimit   = _this.Config["FileSizeLimit"];
			atl.RangeSize       = _this.Config["RangeSize"];
			atl.EncodeType      = _this.Config["EncodeType"];
			atl.License         = _this.Config["License"];
			atl.Company         = _this.Config["Company"];
			atl.PostUrl         = _this.Config["UrlPost"];
			atl.Authenticate    = _this.Config["Authenticate"];
			atl.AuthName        = _this.Config["AuthName"];
			atl.AuthPass        = _this.Config["AuthPass"];
            atl.CryptoType      = _this.Config["CryptoType"];
			atl.Debug           = _this.Config["Debug"];
			atl.LogFile         = _this.Config["LogFile"];
			atl.OnPost          = HttpUploader_Process;
			atl.OnStateChanged  = HttpUploader_StateChanged;
		}
	};
	
	//浏览器环境检查
	_this.Browser = _this.BrowserIE;
	if (_this.ie)
	{
		//if (!_this.Browser.Check()) { window.open(_this.Config["SetupPath"], "_blank"); /*_this.Browser.Setup();*/ } 
	}
	else if (_this.firefox)
	{
		_this.Browser = _this.BrowserFF;
		//if (!_this.Browser.Check()) { window.open(_this.Config["SetupPath"], "_blank"); /*_this.Browser.Setup();*/ }
	} //Chrome
	else if (_this.chrome)
	{
		_this.Browser = _this.BrowserChrome;
		//if (!_this.Browser.Check()) { window.open(_this.Config["SetupPath"], "_blank"); /*_this.Browser.Setup();*/ }
	}

	this.SetupTip = function ()
	{
	    $("#pnlHeader").html('<a href="' + _this.Config["ExePath"] + '" target="_blank">请先安装控件</a>');
	};

	//安装检查
	this.SetupCheck = function ()
	{
		if (this.ie)
		{
			if (!_this.Browser.Check()) { this.SetupTip(); /*_this.Browser.Setup();*/ }
		}
		else if (this.firefox)
		{
			_this.Browser = this.BrowserFF;
			if (!_this.Browser.Check()) { /*this.SetupTip();*/ this.Browser.Setup();}
		} //Chrome
		else if (this.chrome)
		{
			_this.Browser = this.BrowserChrome;
			if (!_this.Browser.Check()) { this.SetupTip(); /*_this.Browser.Setup();*/ }
		}
	};
	this.NeedUpdate = function () {
	    if (this.Browser.Check())
	    {
	        if (this.Browser.NeedUpdate())
	        {
	            _this.pnlHeader.append('<br/><a href="' + _this.Config["ExePath"] + '" target="_blank">控件已更新，请下载最新版</a>');
	        }
	    }
	};

	//安装控件
	this.Install = function ()
	{
		if (!_this.Browser.Check())
		{
			_this.Browser.Setup();
		}
		else
		{
			$("body").empty();
			$("body").append("插件安装成功");
		}
	};

	//安全检查，在用户关闭网页时自动停止所有上传任务。
	this.SafeCheck = function()
	{
		$(window).bind("beforeunload", function()
		{
			if (_this.arrFiles.length > 0)
			{
				event.returnValue = "您还有程序正在运行，确定关闭？";
			}
		});

		$(window).bind("unload", function()
		{ 
			if (_this.arrFiles.length > 0)
			{
				_this.StopAll();
			}
		});
	};

	//加载容器，上传面板，文件列表面板
	this.LoadTo = function(oid)
	{
	    var html = this.GetHtml();
	    var panel = $("#"+oid).html(html);
	    _this.UploaderListDiv = panel.find('div[name="uploadList"]');
		_this.tmpFile    = panel.find('div[name="fileItem"]');
		_this.tmpFolder  = panel.find('div[name="folderItem"]');
		_this.tmpSpliter = panel.find('div[name="lineSplite"]');
		_this.pnlHeader  = panel.find('div[name="pnlHeader"]');
		_this.ffPart     = panel.find('embed[name="ffPart"]').get(0);
		_this.ieDrop     = panel.find('object[name="ieDrop"]').get(0);
        _this.iePart     = panel.find('object[name="iePart"]').get(0);
        _this.ieUper     = panel.find('object[name="ieUper"]').get(0);
        var btnAddFiles  = panel.find('input[name="btnAddFiles"]');
        var btnAddFolder = panel.find('input[name="btnAddFolder"]');
        var btnPasteFile = panel.find('input[name="btnPasteFile"]');
        var btnClearCmp  = panel.find('a[name="lnkClearComplete"]');
        //drag files
        if (null != _this.ieDrop) _this.ieDrop.OnDroped = function ()
		{
            var files = _this.ieDrop.GetFiles();
		    var jsonF = eval("(" + files + ")");
		    if (!$.isEmptyObject(jsonF))
		    {
		        for (var i = 0, l = jsonF.files.length; i < l; ++i) {
		            _this.AddFile(jsonF.files[i].path);
		        }
		    }

		    var folders = _this.ieDrop.GetFolders();
		    var jsonFD = eval("(" + folders + ")");
		    if (!$.isEmptyObject(jsonFD))
		    {
		        for (var i = 0, l = jsonFD.folders.length; i < l; ++i) {
		            _this.AddFolder(jsonFD.folders[i]);
		        }
		    }

		    _this.PostFirst();
		};

	    //添加多个文件
		btnAddFiles.click(function () { _this.OpenFileDialog(); });
	    //添加文件夹
		btnAddFolder.click(function () { _this.OpenFolderDialog(); });
	    //粘贴文件
		btnPasteFile.click(function () { _this.PasteFiles(); });
	    //清空已完成文件
		btnClearCmp.click(function () { _this.ClearComplete(); });

		_this.SafeCheck();
		_this.InitContainer();
		_this.Browser.Init(); //
	};
	
	//初始化容器
	this.InitContainer = function()
	{
		var cbItemLast = null;
		$("#cbHeader li").each(function(n)
		{
			if (this.className == "hover")
			{
				cbItemLast = this;
			}

			$(this).click(function()
			{
				$("ul[name='cbItem']").each(function(i)
				{
					this.style.display = i == n ? "block" : "none"; /*确定主区域显示哪一个对象*/
				});
				if (cbItemLast) cbItemLast.className = "";

				if (this.className == "hover")
				{
					this.className = "";
				}
				else
				{
					this.className = "hover";
				}
				cbItemLast = this;
			});
		});
	};

    //清除已完成文件
	this.ClearComplete = function()
	{
		for(var i = 0 ,l=_this.arrFilesComplete.length; i < l; ++i)
		{
			_this.Delete(_this.arrFilesComplete[i].FileID);
		}
		_this.arrFilesComplete.length = 0;
	};

	//上传队列是否已满
	this.IsPostQueueFull = function()
	{
		//目前只支持同时上传三个文件
		if (_this.arrFiles.length > 0)
		{
			return true;
		}
		return false;
	};

	//添加一个上传ID
	this.AppendUploadId = function(fid)
	{
		_this.arrFiles.push(fid);
	};

    /*
	从当前上传ID列表中删除指定项。
	此函数将会重新构造一个Array
	*/
	this.RemoveUploadId = function (fid) {
	    if (_this.arrFiles.length < 1) return;

	    for (var i = 0, l = _this.arrFiles.length; i < l; ++i) {
	        if (_this.arrFiles[i] == fid) {
	            _this.arrFiles.remove(fid);
	        }
	    }
	};
	
	//添加到上传队列
	this.AppendQueue = function(fid)
	{
		_this.UploadQueue.push(fid);
	};

	//从队列中删除
	this.RemoveQueue = function(fid)
	{ 
		if (_this.UploadQueue.length < 1) return;
		
		for (var i = 0, l = _this.UploadQueue.length; i < l; ++i)
		{
			if (_this.UploadQueue[i] == fid)
			{
				_this.UploadQueue.remove(fid);
			}
		}
	};
	
	//添加到未上传ID列表，(停止，出错)
	this.AppendUnUploadIds = function(fid)
	{
		_this.arrIdsErrStop.push(fid);
	};
	
	//从未上传ID列表删除，(上传完成)
	this.RemoveUnUploadIds = function(fid)
	{ 
		if (_this.arrIdsErrStop.length < 1) return;
		
		for (var i = 0, l = _this.arrIdsErrStop.length; i < l; ++i)
		{
			if (_this.arrIdsErrStop[i] == fid)
			{
				_this.arrIdsErrStop.remove(fid);
			}
		}
	};

	//停止所有上传项
	this.StopAll = function()
	{
		for (var i = 0, l = _this.arrFiles.length; i < l; ++i)
		{
			_this.UploaderList[_this.arrFiles[i]].StopManual();
		}
		_this.arrFiles.length = 0;
	};

	/*
	添加到上传列表
	参数
		fid 上传项ID
		task 新的上传对象
	*/
	this.AppenToUploaderList = function(fid, task)
	{
		_this.UploaderList[fid] = task;
		_this.UploaderListCount++;
	};

	/*
	添加到上传列表层（弃用）
	1.添加到上传列表层
	2.添加分隔线
	参数：
		fid 上传项ID
		uploaderDiv 新的上传信息层
	返回值：
		新添加的分隔线
	*/
	this.AppendToUploaderListDiv = function(fid, uploaderDiv)
	{
		//_this.UploaderListDiv.appendChild(uploaderDiv);
		$(_this.UploaderListDiv).append(uploaderDiv);

		var split = "<div class=\"Line\" style=\"display:block;\" id=\"FilePostLine" + fid + "\"></div>";
		//_this.UploaderListDiv.insertAdjacentHTML("beforeEnd", split);
		$(_this.UploaderListDiv).append(split);
		var obj = document.getElementById("FilePostLine" + fid);
		return obj;
	};

	//传送当前队列的第一个文件
	this.PostFirst = function ()
	{
		//上传列表不为空
		if (_this.UploadQueue.length > 0)
		{
		    while (_this.UploadQueue.length > 0)
			{
				//上传队列已满
				if (_this.IsPostQueueFull()) return;
				var index = _this.UploadQueue.shift();
			    _this.UploaderList[index].Post();
			}
		}
	};
	
	//启动下一个传输
	this.PostNext = function()
	{
		if (this.IsPostQueueFull()) return; //上传队列已满

		if (this.UploadQueue.length > 0)
		{
		    var index = this.UploadQueue.shift();
			var obj = this.UploaderList[index];

			//空闲状态
			if (HttpUploaderState.Ready == obj.State)
			{
				obj.Post();
			}
		} //全部上传完成
		else
		{
			if (this.arrIdsErrStop.join("").length < 1)
			{
				//alert("所有文件上传完毕。");
			}
		}
	};

	/*
	验证文件名是否存在
	参数:
	[0]:文件名称
	*/
	this.Exist = function()
	{
		var fn = arguments[0];

		for (a in _this.UploaderList)
		{
			if (_this.UploaderList[a].LocalFile == fn)
			{
				return true;
			}
		}
		return false;
	};

	/*
	根据ID删除上传任务
	参数:
		fid 上传项ID。唯一标识
	*/
	this.Delete = function(fid)
	{
		var obj = _this.UploaderList[fid];
		if (null == obj) return;

		_this.RemoveQueue(fid); //从队列中删除
		_this.RemoveUnUploadIds(fid);//从未上传列表中删除

	    //删除div
		obj.div.remove();
		obj.spliter.remove();
		//_this.UploaderListDiv.removeChild(obj.div);
		//删除分隔线
		//_this.UploaderListDiv.removeChild(obj.spliter);
		obj.LocalFile = "";
		obj.Dispose();
	};

	/*
		设置文件过滤器
		参数：
			filter 文件类型字符串，使用逗号分隔(exe,jpg,gif)
	*/
	this.SetFileFilter = function(filter)
	{
		_this.FileFilter.length = 0;
		_this.FileFilter = filter.split(",");
	};

	/*
	判断文件类型是否需要过滤
	根据文件后缀名称来判断。
	*/
	this.NeedFilter = function(fname)
	{
		if (_this.FileFilter.length == 0) return false;
		var exArr = fname.split(".");
		var len = exArr.length;
		if (len > 0)
		{
			for (var i = 0, l = _this.FileFilter.length; i < l; ++i)
			{
				//忽略大小写
				if (_this.FileFilter[i].toLowerCase() == exArr[len - 1].toLowerCase())
				{
					return true;
				}
			}
		}
		return false;
	};
	
	//打开文件选择对话框
	this.OpenFileDialog = function()
	{
		_this.Browser.OpenFileDialog();
	};
	
	//打开文件夹选择对话框
	this.OpenFolderDialog = function()
	{
		_this.Browser.OpenFolderDialog();
	};

	//粘贴文件
	this.PasteFiles = function()
	{
		_this.Browser.PasteFiles();
	};
	
	//检查续传大小是否合法。必须为全数字
	this.IsNumber = function(num)
	{
		var reg = /\D/;
		return reg.test(num);
	};

	/*
		添加一个续传文件
		参数：
			filePath 本地文件路径(urlencode编码)。D:\\Soft\\QQ2010.exe
			postedLength 已上传字节。控件将从此位置开始续传数据
			percent 已上传百分比。示例：20%
			md5 文件MD5值。32个字符
			idSvr 与服务器对应的fid，必须唯一
	*/
	this.ResumeFile = function(filePath, postedLength, percent, md5, idSvr,pathSvr)
	{
		//本地文件名称存在
		if (_this.Exist(filePath)) return;

		var fileName = filePath.substr(filePath.lastIndexOf("\\") + 1);
		var fid = _this.UploaderListCount;

		var uper = this.AddFile(filePath);
		uper.pPercent.text(percent);
		uper.FileLengthSvr = postedLength;
		uper.MD5 = md5;
		uper.fid = idSvr;
		uper.pathSvr = pathSvr;//add(2015-03-19):
		uper.pButton.text("续传");
		uper.pBtnDel.show();
		uper.pProcess.css("width",percent);
		this.RemoveQueue(uper.FileID);//从队列中删除
	};
	
	//添加已上传完的文件
	this.addFileCmp = function(f)
	{
		var pathLoc = f.pathLoc;
		var lenSvr = f.PostedLength;
		//本地文件名称存在
		if (_this.Exist(pathLoc)) return;

		var fileName = pathLoc.substr(pathLoc.lastIndexOf("\\") + 1);
		var fid = _this.UploaderListCount;

		var uper = this.AddFile(pathLoc);
		uper.pPercent.text("100%");
		uper.FileLengthSvr = lenSvr;
		uper.MD5 = f.FileMD5;
		uper.fid = f.idSvr;
		uper.pathSvr = f.pathSvr;//add(2015-03-19):
		uper.pMsg.text("");
		uper.pButton.text("");
		uper.pProcess.css("width","100%");
		this.RemoveQueue(uper.FileID);//从队列中删除
	};

	/*
		添加一个文件到上传队列
		参数:
			filePath 包含完整路径的文件名称。D:\\Soft\\QQ.exe
	*/
	this.AddFile = function(filePath)
	{
		//本地文件名称存在
		if (_this.Exist(filePath)) return;
		//此类型为过滤类型
		if (_this.NeedFilter(filePath)) return;

		var fileName = filePath.substr(filePath.lastIndexOf("\\") + 1);
		var fid = _this.UploaderListCount;
		_this.AppendQueue(fid);//添加到队列

		var ui = _this.tmpFile.clone();
		_this.UploaderListDiv.append(ui);//添加到上传列表面板
		ui.css("display", "block");
		ui.attr("id", "item" + fid);

		var divFileName = ui.find("div[name='fileName']");
		var divfileSize = ui.find("div[name='fileSize']")
		var divProcess 	= ui.find("div[name='process']");
		var divMsg 		= ui.find("div[name='msg']");
		var btnCancel 	= ui.find("span[name='btnCancel']");
		var btnDel 		= ui.find("span[name='btnDel']");
		var divPercent	= ui.find("div[name='percent']");
		
		var upFile = new HttpUploader(fid, filePath, _this);
		divFileName.text(fileName)
			.attr("title", fileName);
		divfileSize.text(upFile.FileSize);
		upFile.pProcess = divProcess;
		upFile.pMsg = divMsg;
		divMsg.text("");
		upFile.pButton = btnCancel;
		btnCancel.attr("fid", fid)
			.attr("domid", "item" + fid)
			.attr("lineid", "FilePostLine" + fid)
			.click(function()
			{
				var obj = $(this);
				var objup = _this.UploaderList[obj.attr("fid")];

				switch (obj.text())
				{
					case "暂停":
					case "停止":
						upFile.Stop(obj.attr("fid"));
						break;
					case "取消":
						{
							upFile.Stop();
							_this.Delete(upFile.FileID);
						}
						break;
					case "续传":
							if (!_this.IsPostQueueFull())
							{
								_this.AppendUploadId(upFile.FileID);
								upFile.Upload();
							}
							else
							{
								upFile.Ready();
								//添加到队列
								_this.AppendQueue(upFile.FileID);
								obj.text("停止");
							}
						break;
					case "重试":
						upFile.Post();
						break;
				}
			});
		upFile.pBtnDel = btnDel;
		btnDel.click(function(){upFile.Delete();});
		upFile.pPercent = divPercent;
		divPercent.text("0%");
		upFile.Manager = _this;
		upFile.div = ui;

		//添加到上传列表
		this.AppenToUploaderList(fid, upFile);
		//添加到上传列表层
		var sp = _this.tmpSpliter.clone();
		sp.css("display", "block");
		_this.UploaderListDiv.append(sp);
		upFile.spliter = sp;
		//upFile.Post(); //开始上传
		upFile.Ready(); //准备
		return upFile;
	};

	//添加文件夹,json为文件夹信息字符串
	this.AddFolder = function (json)
	{
		var fdJson = eval( "(" + json + ")" );
		//本地文件夹存在
		if (_this.Exist(fdJson.pathLoc)) return;

		var fid = _this.UploaderListCount;
		_this.AppendQueue(fid);//添加到队列

		var ui = _this.tmpFolder.clone();
		_this.UploaderListDiv.append(ui);//添加到上传列表面板
		ui.css("display", "block")
			.attr("id", "item" + fid);

		var divFileName = ui.find("div[name='fileName']");
		var divfileSize = ui.find("div[name='fileSize']")
		var divProcess 	= ui.find("div[name='process']");
		var divMsg 		= ui.find("div[name='msg']");
		var btnCancel 	= ui.find("span[name='btnCancel']");
		var btnDel 		= ui.find("span[name='btnDel']");
		var divPercent	= ui.find("div[name='percent']");

		var fdTask = new FolderUploader(fid, json, _this);
		fdTask.pathLocal = fdJson.pathLoc;//
		divFileName.text(fdJson.name)
					.attr("title", fdJson.name+"\n文件："+fdJson.filesCount+"\n文件夹："+fdJson.foldersCount+"\n大小："+fdJson.size);
		divfileSize.text(fdJson.size);
		fdTask.pProcess = divProcess;
		fdTask.pMsg = divMsg;
		fdTask.pMsg.text("");
		fdTask.pButton = btnCancel;
		fdTask.pButton.attr("fid", fid)
					.attr("domid", "item" + fid)
					.attr("lineid", "FilePostLine" + fid)
					.click(function()
					{
						var obj = $(this);			

						switch (obj.text())
						{
							case "暂停":
							case "停止":
								fdTask.Stop();
								break;
							case "取消":
								{
									fdTask.Stop();
									_this.Delete(fdTask.FileID);
								}
								break;
							case "续传":
									if (!_this.IsPostQueueFull())
									{
										fdTask.Upload();
										fdTask.pButton.text("停止");
									}
									else
									{
										fdTask.Ready();
										//添加到队列
										_this.AppendQueue(fdTask.FileID);
										fdTask.pButton.text("停止");
									}
								break;
							case "重试":
								fdTask.Post();
								break;
						}
					});
		fdTask.pBtnDel = btnDel;
		fdTask.pBtnDel.click(function(){fdTask.Delete();});
		fdTask.pPercent = divPercent;
		fdTask.pPercent.text("0%");
		fdTask.div = ui;

		//添加到上传列表
		this.AppenToUploaderList(fid, fdTask);
		var sp = _this.tmpSpliter.clone();
		sp.css("display", "block");
		_this.UploaderListDiv.append(sp);
		fdTask.spliter = sp;
		fdTask.Ready(); //准备
		return fdTask;
	};

	/*
		续传文件夹
		参数：
			fdInf 文件夹JSON信息
			pathLoc 文件夹本地路径。
			postlen 已上传长度
			percent 已上传百分比较
			idSvr 文件夹在服务端的ID
	*/
	this.ResumeFolder = function (fdInf/*pathLoc, postlen, percent, idSvr*/)
	{
		var fd = this.AddFolder(fdInf.fd_json);
		fd.folderInit = true;
		fd.lenPosted = parseInt(fdInf.PostedLength);//取值异常
		fd.pPercent.text(fdInf.PostedPercent);
		fd.idSvr = fdInf.idSvr;
	    //
		var json = eval("(" + fdInf.fd_json + ")");
		fd.fdRoot = json;
		fd.LocalFile = json.pathLoc; //
		fd.FileName = json.name;
		fd.FileSize = json.size;
		fd.lenTotal = parseInt(json.length);
		if (null == json.files) {
		    //fd.PostComplete();
		    alert("文件为空");
		    return;
		}
		for (var i = 0, l = json.files.length; i < l; ++i) {
		    fd.AddFile( json.files[i] );//未解码
		}
	};
}

/*
	文件夹上传对象，内部包含多个HttpUploader对象
	参数：
		json 文件夹信息结构体，一个JSON对象。
*/
function FolderUploader(idLoc,jsonStr,mgr)
{
	//this.pMsg;
	//this.pProcess;
	//this.pPercent;
	//this.pButton
	//this.div
	//this.split
	//this.FileID
	this.FileID = idLoc;
	this.idSvr = 0;//服务端ID
	this.isFolder = true; //是文件夹
	this.folderInit = false;//文件夹已初始化
	this.manager = mgr;
	this.arrFiles = new Array(); //子文件列表(未上传文件列表)，存HttpUploader对象
	this.arrFilesComplete = new Array();//已上传完的文件列表，存储HttpUploader对象
	this.fdRoot = null; //根目录,JSON对象
	this.Config = mgr.Config;
	this.Fields = mgr.Fields;
	this.ActiveX = mgr.ActiveX;
	this.Browser = mgr.Browser;
	this.uploadCur = null;//当前上传项
	this.LocalFile = ""; //判断是否存在相同项
	this.FileName = "";
	this.lenPosted = 0;//已上传大小
	this.lenTotal = 0;//文件夹总大小

    //格式化秒，将秒转换成00:00:00
	this.FormatSecond = function (s)
	{
	    var t;
	    if (s > -1)
	    {
	        var hour = Math.floor(s / 3600);
	        var min = Math.floor(s / 60) % 60;
	        var sec = s % 60;
	        var day = parseInt(hour / 24);
	        if (day > 0) {
	            hour = hour - 24 * day;
	            t = day + "day " + hour + ":";
	        }
	        else t = hour + ":";
	        if (min < 10) { t += "0"; }
	        t += min + ":";
	        if (sec < 10) { t += "0"; }
	        t += sec;
	    }
	    return t;

	};

	//准备
	this.Ready = function()
	{
		this.pMsg.text("正在上传队列中等待...");
		this.State = HttpUploaderState.Ready;
	};

	this.AddFile = function (jsFile)
	{
		var f = new HttpUploader(this.arrFiles.length, jsFile.pathLoc, this.manager);
		f.root = this;
		f.pMsg = this.pMsg;
		f.pButton = this.pButton;
		f.pidSvr = jsFile.pidSvr;
		f.idSvr = jsFile.idSvr;
		f.pathSvr = jsFile.pathSvr;//change(2015-03-19):urlEncode编码
		f.FileLengthSvr = jsFile.postLength;
		f.lenPosted = parseInt(jsFile.postLength);
		f.length = parseInt(jsFile.length);
		f.lenCur = f.length - f.lenPosted;//当前要上传的长度
		f.MD5 = jsFile.md5;
		f.fid = jsFile.idSvr;
		//f.ResetFields();//续传设置附加参数
		this.arrFiles.push(f);
	};

	//上传，创建文件夹结构信息
	this.Post = function ()
	{
	    this.manager.AppendUploadId(this.FileID);//添加到队列中
	    this.State = HttpUploaderState.Posting;
        //如果文件夹已初始化，表示续传。
	    if (this.folderInit)
	    {
	        this.pButton.text("停止");
	        this.PostFirst();
	        return;
	    }

		var obj = this;
		//在此处增加服务器验证代码。
		obj.pMsg.text("初始化...");
		
		//php json_encode无法解析\\，所以将\\转换为/
		jsonStr.replace("\\","/");

		$.ajax({
			type: "POST"
			//, dataType: 'jsonp'
			//, jsonp: "callback" //自定义的jsonp回调函数名称，默认为jQuery自动生成的随机函数名
			, url: obj.Config["UrlFdCreate"]
			, data: { uid: obj.Fields["uid"], folder: encodeURIComponent(jsonStr), time: new Date().getTime() }
			, success:function (msg)
			{
				var json = eval("(" + decodeURIComponent(msg) + ")");
				obj.fdRoot = json;
				obj.LocalFile = json.pathLoc; //
				obj.FileName = json.name;
				obj.FileSize = json.size;
				obj.lenTotal = parseInt(json.length);
				if (null == json.files)
				{
					obj.PostComplete();
					return;
				}
				for (var i = 0, l = json.files.length; i < l; ++i)
				{
					obj.AddFile(json.files[i]);
				}
				obj.pButton.text("停止");
				obj.folderInit = true;
				if (obj.State == HttpUploaderState.Posting)
				{
				    obj.PostFirst();
				}
			}
			, error: function (req, txt, err)
			{
				alert("向服务器发送文件夹信息错误！" + req.responseText);
				obj.folderInit = false;
				obj.pMsg.text("向服务器发送文件夹信息错误");
				obj.pButton.text("重试");
			}
			, complete: function (req, sta) { req = null; }

		});
	};
	//续传
	this.Upload = function ()
	{
	    this.State = HttpUploaderState.Posting;
	    this.manager.AppendUploadId(this.FileID);
	    if (this.folderInit)
		{
			this.PostFirst();
		}
		else
		{
			this.Post();
		}
	};

	//上传队列中的第一个
	this.PostFirst = function ()
	{
		var f = this.arrFiles.shift();
		this.uploadCur = f;
        //远程文件大小和本地文件大小相同的情况
		if (f.lenPosted == f.length)
		{
		    this.ItemPostComplete(f);
		    return;
		}

		f.Post();
	};

	this.PostNext = function ()
	{
	    if (this.State == HttpUploaderState.Stop) return;
		if (this.arrFiles.length > 0)
		{
			this.PostFirst();
		}
		else
		{
			this.PostComplete();
		}
	};

	this.PostError = function ()
	{
		var upCur = this.uploadCur;
		this.pMsg.text(HttpUploaderErrorCode[upCur.ATL.GetErrorCode()]);
		//文件大小超过限制,文件大小为0
		if (10 == upCur.ATL.GetErrorCode()
			|| 201 == upCur.ATL.GetErrorCode())
		{
			//this.pButton.text("取消");
		}
		else
		{
			//this.pButton.text("续传");
		}
		this.State = HttpUploaderState.Error;
		//从上传列表中删除
		//this.manager.RemoveUploadId(this.FileID);
		//添加到未上传列表
		//this.manager.AppendUnUploadIds(this.FileID);
		this.PostNext();
	};

	//所有文件全部上传完成
	this.PostComplete = function ()
	{
		this.pButton.css("display", "none");
		this.pProcess.css("width", "100%");
		this.pPercent.text("100%");
		//obj.pMsg.text("上传完成");
		this.State = HttpUploaderState.Complete;
		this.uploadCur = null;
		this.manager.arrFilesComplete.push(this);
		//从上传列表中删除
		this.manager.RemoveUploadId(this.FileID);
		//从未上传列表中删除
		this.manager.RemoveUnUploadIds(this.FileID);
		//obj.Dispose();
		this.pMsg.text("共"+this.fdRoot.filesCount+"个文件，成功上传"+this.arrFilesComplete.length+"个文件");
		var ref = this;

		$.ajax({
			type: "GET"
			, dataType: 'jsonp'
			, jsonp: "callback" //自定义的jsonp回调函数名称，默认为jQuery自动生成的随机函数名
			, url: this.Config["UrlFdComplete"]
			, data: { uid: this.Fields["uid"], fid: this.fdRoot.idSvr, time: new Date().getTime() }
			, success:function (msg)
			{
				ref.manager.PostNext();
			}
			, error: function () { alert("向服务器发送文件夹Complete信息错误！" + req.responseText); }
			, complete: function (req, sta) { req = null; }
		});
	};

	//上传进度，逻辑：根据已上传文件数计算总进度
	this.ItemPostProcess = function (obj, speed, postedLength, percent, times)
	{
	    var brow = this.manager.Browser;
	    var objLen = parseInt(obj.ATL.GetFileLenSvr());
		var lenPostedCur = this.lenPosted + objLen;
		var lenLast = this.lenTotal - lenPostedCur;
		var percentTotal = Math.round( (lenPostedCur / this.lenTotal) * 100) +"%";
		this.pPercent.text(percentTotal);
		this.pProcess.css("width", percentTotal);
        //计算总剩余时间
		var speedNum = parseInt(obj.ATL.GetSpeed());
		var timeFmt = times;
		if (speedNum > 0 && lenPostedCur > 0)
		{
		    var timeTotal = Math.round((this.lenTotal - lenPostedCur) / speedNum) * 100;
		    timeFmt = this.FormatSecond(timeTotal);
		}
	    var str = "已上传:" + brow.FormatByte(lenPostedCur.toString()) + " 速度:" + speed + "/S 剩余:" + timeFmt;
		this.pMsg.text(str);
	};

	//文件项上传完毕。
	this.ItemPostComplete = function (obj)
	{
	    obj.Dispose();//回收资源
	    //这里有BUG，不能加整个文件长度。只能加剩余的长度。如果文件传了50%，再加整个文件长度就相当于多加了50%。
	    this.lenPosted += obj.lenCur;//parseInt(obj.ATL.GetFileLength());
		this.arrFilesComplete.push(obj);
		this.uploadCur = null;
		var ref = this;

		$.ajax({
			type: "GET"
			, dataType: 'jsonp'
			, jsonp: "callback" //自定义的jsonp回调函数名称，默认为jQuery自动生成的随机函数名
			, url: obj.Config["UrlComplete"]
			, data: { md5: obj.MD5, uid: obj.Fields["uid"], fid: obj.idSvr, time: new Date().getTime() }
			, success: function (msg)
			{
				//添加到文件列表
				ref.PostNext();
			}
			, error: function () { alert("向服务器发送Complete信息错误！"); }
			, complete: function (req, sta) { req = null; }
		});
	};

	this.ItemQuickComplete = function (obj)
	{
	    obj.Dispose();
	    this.lenPosted += obj.lenCur;//parseInt(obj.ATL.GetFileLength());
	    this.arrFilesComplete.push(obj);
	    this.PostNext();
	};
	//MD5错误
	this.ItemMd5Error = function (obj)
	{
		this.pButton.text("重试");
		this.arrFiles.push(this.uploadCur);
		this.uploadCur = null;
	};

	//一般在StopAll()中调用
	this.StopManual = function ()
	{ 
	    if (this.uploadCur) this.uploadCur.ATL.Stop();
	    this.State = HttpUploaderState.Stop;
	};
	//手动点击“停止”按钮时
	this.Stop = function ()
	{
	    this.State = HttpUploaderState.Stop;
	    if (HttpUploaderState.Ready == this.State)
	    {
	        this.pButton.text("续传");
	        this.pMsg.text("传输已停止....");
	        this.pBtnDel.show();
	        this.manager.RemoveQueue(this.FileID);
	        this.manager.AppendUnUploadIds(this.FileID);//添加到未上传列表
	        this.PostNext();
	        return;
	    }

	    if (this.uploadCur)
		{
			this.uploadCur.ATL.Stop();
			this.arrFiles.push(this.uploadCur);
			this.uploadCur = null;
		}
		this.manager.RemoveUploadId(this.FileID);
		this.manager.AppendUnUploadIds(this.FileID);
		this.pButton.text("续传");
	};

	//从上传列表中删除上传任务
	this.Delete = function ()
	{ 
		//this.Dispose();
		this.manager.Delete(this.FileID);
	};

	this.Dispose = function () { };
}

//文件上传对象
function HttpUploader(fileID, filePath, mgr)
{
    var _this = this;
	//this.pMsg;
	//this.pProcess;
	//this.pPercent;
	//this.pButton
	//this.div
	//this.split
	//this.FileID
    this.pidSvr = "";//文件夹ID
    this.idSvr = "";//
    this.pathSvr = "";//文件在服务器中的路径。
	this.isFolder = false; //不是文件夹
	this.root = null;//根级文件夹对象。一般为FolderUploader
	this.ie = mgr.ie;
	this.firefox = mgr.firefox;
	this.chrome = mgr.chrome;
	this.ffPart = mgr.ffPart;
	this.Browser = mgr.Browser;
	this.Manager = mgr; //上传管理器指针
	this.event = mgr.event;
	this.Config = mgr.Config;
	this.Fields = mgr.Fields;
	this.ActiveX = mgr.ActiveX;
	this.UploaderPool = mgr.UploaderPool;
	this.UploaderPoolFF = mgr.UploaderPoolFF;
	this.State = HttpUploaderState.None;
	this.MD5 = "";
	this.FileName = filePath.substr(filePath.lastIndexOf("\\") + 1);
	this.LocalFile = filePath;
	//this.FileID = this.ATL.FileID;
	this.FileID = fileID;
	this.PathLoc = filePath;
	this.PathLocal = encodeURIComponent(filePath); //URL编码后的本地路径
	this.PathRel = ""; //文件在服务器中的相对地址。示例：http://www.ncmem.con/upload/201204/03/QQ2012.exe
	this.FileLengthSvr = "0";
	this.fid = 0; //与服务器数据库对应的fid
	this.uid = this.Fields["uid"];
	this.FileSize = this.Browser.GetFileSize(this.LocalFile); //格式化后的文件大小 50MB
	this.FileLength = this.Browser.GetFileLength(this.LocalFile);//以字节为单位的字符串

	//初始化控件
	this.ATL = {
		"Create": function()
		{
		    if (this.inited) return;
		    if (_this.UploaderPool.length > 0)
		    {
		        this.com = _this.UploaderPool.pop();
		    }
		    else
		    {
		        this.com = new ActiveXObject(_this.ActiveX["Uploader"]);
		    }
			this.com.Object			= _this;
			this.com.LocalFile		= _this.PathLoc;
			this.com.License		= _this.Config["License"];
			this.com.Company		= _this.Config["Company"];
            this.com.PostUrl		= _this.Config["UrlPost"];
			this.com.Debug			= _this.Config["Debug"];
			this.com.LogFile		= _this.Config["LogFile"];
            this.com.Authenticate	= _this.Config["Authenticate"];
            this.com.AuthName		= _this.Config["AuthName"];
            this.com.AuthPass		= _this.Config["AuthPass"];
            this.com.CryptoType     = _this.Config["CryptoType"];
            this.com.EncodeType		= _this.Config["EncodeType"];
            this.com.FileSizeLimit	= _this.Config["FileSizeLimit"];
            this.com.RangeSize		= _this.Config["RangeSize"];
            this.com.PostedLength   = _this.FileLengthSvr;
            this.com.MD5            = _this.MD5;
            this.com.Cookie         = document.cookie;//
            this.com.OnPost			= HttpUploader_Process;
			this.com.OnStateChanged = HttpUploader_StateChanged;
			this.inited = true;
		}
		//get
		, "GetFileSize": function () { return this.com.FileSize; }
		, "GetFileLength": function () { return this.com.FileLength; }
		, "GetResponse": function () { return this.com.Response; }
		, "GetMD5": function () { return this.com.MD5; }
		, "GetMd5Percent": function () { return this.com.Md5Percent; }
		, "GetFileLenSvr": function () { return this.com.PostedLength; }
        , "SetFileLenSvr": function (len) { this.com.PostedLength = len; }
		, "GetSpeed": function () { return this.com.Speed; }
		, "GetErrorCode": function () { return this.com.ErrorCode; }
		//methods
		, "CheckFile": function ()
		{
		    if (!this.inited)
		    {
		        this.Create();
		    }
		    this.com.CheckFile();
		}
		, "Post": function ()
		{
		    if (!this.inited)
		    {
		        this.Create();
		    }
		    _this.ResetFields();
		    this.com.PostedLength = _this.FileLengthSvr;
		    this.com.Post();
		}
		, "Stop": function ()
		{
		    this.com.Stop();
		    this.inited = false;//取消已初始化标识
		    //保存当前已上传长度
		    //_this.FileLengthSvr = this.com.FileLengthSvr;
		}
		, "ClearFields": function () { this.com.ClearFields(); }
		, "AddField": function (fn, fv) { this.com.AddField(fn, fv); }
		, "Dispose": function ()
		{
		    this.inited = false;//取消已初始化标识
		    if (this.com)
		    {
		        //_this.FileLengthSvr = this.com.FileLengthSvr;
		        //回收资源，以便重复使用。
		        _this.UploaderPool.push(this.com);
		    }
		}
		, "IsPosting": function ()
		{
		    if (!this.inited) return false;
		    return this.com.IsPosting();
		}
		//property
		, "com": null
        , "inited": false
		, "idSign": 0//由控件分配的
	};
	//Firefox 插件
	this.ATLFF = {
		"Create": function ()
		{
		    if (this.inited) return;
		    if (_this.UploaderPoolFF.length > 0)
		    {
		        this.idSign = _this.UploaderPoolFF.pop();
		        this.Atl.SetLocalFile(this.idSign, _this.LocalFile);
		    }
		    else
		    {
		        this.idSign = this.Atl.AddFile(_this.LocalFile);
		    }
		    this.Atl.SetObject(this.idSign, _this);
		    this.Atl.SetPostedLength(this.idSign, _this.FileLengthSvr.toString());
		    this.Atl.SetMD5(this.idSign, _this.MD5);
		    this.Atl.SetCookie(this.idSign, document.cookie);
		    this.inited = true;
		}
		//get
		, "GetFileSize": function () { return this.Atl.GetFileSize(this.idSign); }
		, "GetFileLength": function () { return this.Atl.GetFileLength(this.idSign); }
		, "GetResponse": function () { return this.Atl.GetResponse(this.idSign); }
		, "GetMD5": function () { return this.Atl.GetMD5(this.idSign); }
		, "GetMd5Percent": function () { return this.Atl.GetMd5Percent(this.idSign); }
		, "GetFileLenSvr": function () { return this.Atl.GetPostedLength(this.idSign); }
        , "SetFileLenSvr": function (len) { this.Atl.SetPostedLength(this.idSign,len); }
		, "GetSpeed": function () { return this.Atl.GetSpeed(this.idSign); }
		, "GetErrorCode": function () { return this.Atl.GetErrorCode(this.idSign); }
		//methods
		, "CheckFile": function ()
		{
		    if (!this.inited)
		    {
		        this.Create();
		    }
		    this.Atl.CheckFile(this.idSign);
		}
		, "Post": function ()
		{
		    if (!this.inited)
		    {
		        this.Create();
		    }
		    _this.ResetFields();
		    this.SetFileLenSvr(_this.FileLengthSvr);
		    this.Atl.Post(this.idSign);
		}
		, "Stop": function ()
		{
		    this.Atl.Stop(this.idSign);
		    this.inited = false;//
		    //_this.FileLengthSvr = this.Atl.GetFileLengthSvr(this.idSign);
		}
		, "ClearFields": function () { this.Atl.ClearFields(this.idSign); }
		, "AddField": function (fn, fv) { this.Atl.AddField(this.idSign, fn, fv); }
		, "Dispose": function ()
		{
		    this.inited = false;//
		    //_this.FileLengthSvr = this.Atl.GetFileLengthSvr(this.idSign);
		    _this.UploaderPoolFF.push(this.idSign);
		}
		, "IsPosting": function ()
		{
		    if (!this.inited) return false;
		    return this.Atl.IsPosting(this.idSign);
		}
		//property
		, "Atl": _this.ffPart
        , "inited":false
		, "idSign": "0"//由控件分配的标识符，全局唯一。
	};
	//是Firefox或Chrome浏览器
	if (this.firefox||this.chrome){this.ATL = this.ATLFF;}
	
	//重置附加信息
	this.ResetFields = function()
	{
		//添加附加信息
		this.ATL.ClearFields(); //清空附加字段
//		$.each(upRef.Fields, function(name, val)
//		{
//			upRef.ATL.AddField(name, val);
//		});
		for (var field in this.Fields)
		{
			this.ATL.AddField(field, this.Fields[field].toString());
		}
		this.ATL.AddField("fid", this.fid.toString());
		this.ATL.AddField("uid", this.uid.toString());
		this.ATL.AddField("pathSvr", this.pathSvr);//add(2015-03-19):
	};
	
	//准备
	this.Ready = function()
	{
		//this.pButton.style.display = "none";
		this.pMsg.text("正在上传队列中等待...");
		this.State = HttpUploaderState.Ready;
	};

	this.Post = function()
	{
		if(null == this.root) this.Manager.AppendUploadId(this.FileID);
		if (this.MD5.length > 0)
		{
			this.Upload();
		}
		else
		{
			//alert(this.ATL.FileID);
			this.CheckFile();
		}
	};

    //检查文件
	this.CheckFile = function ()
	{
	    this.State = HttpUploaderState.MD5Working;
	    this.ATL.CheckFile();
	};
	
	//上传
	this.Upload = function ()
	{
		//正在上传
		if (this.ATL.IsPosting())
		{
			this.Manager.RemoveUploadId(this.FileID);
		}
		else
		{
			//文件项
			if (null == this.root)
			{
				this.pButton.css("display", "");
				this.pButton.text("停止");
				this.pBtnDel.hide();
			}
			//this.pMsg.innerText = "正在连接服务器....";
			this.State = HttpUploaderState.Posting;
			
			//已上传完
			if (this.FileLengthSvr == this.FileLength)
			{
				this.UploadComplete();
			}
			else
			{
				this.ATL.Post();
			}
		}
	};
	this.UploadError = function ()
	{ 
		this.pMsg.text(HttpUploaderErrorCode[this.ATL.GetErrorCode()]);
		//文件大小超过限制,文件大小为0
		if (10 == this.ATL.GetErrorCode()
			|| 201 == this.ATL.GetErrorCode())
		{
			this.pButton.text("取消");
		}
		else
		{
			this.pButton.text("续传");
		}
		this.State = HttpUploaderState.Error;
		//从上传列表中删除
		this.Manager.RemoveUploadId(this.FileID);
		//添加到未上传列表
		this.Manager.AppendUnUploadIds(this.FileID);
		this.Dispose();
		this.PostNext();
	};
	this.UploadComplete = function ()
	{
	    this.event.postComplete(this);//biz event
		this.pButton.css("display", "none");
		this.pProcess.css("width", "100%");
		this.pPercent.text("100%");
		this.pMsg.text("上传完成");
		this.Manager.arrFilesComplete.push(this);
		this.State = HttpUploaderState.Complete;
		//从上传列表中删除
		this.Manager.RemoveUploadId(this.FileID);
		//从未上传列表中删除
		this.Manager.RemoveUnUploadIds(this.FileID);
		this.Dispose();
		var obj = this;

		$.ajax({
			type: "GET"
			, dataType: 'jsonp'
			, jsonp: "callback" //自定义的jsonp回调函数名称，默认为jQuery自动生成的随机函数名
			, url: obj.Config["UrlComplete"]
			, data: { md5: obj.MD5, uid: obj.Fields["uid"], fid: obj.fid, time: new Date().getTime() }
			, success:function (msg)
			{
				obj.PostNext();
			}
			, error: function () { alert("文件-向服务器发送Complete信息错误！" + req.responseText); }
			, complete: function (req, sta) { req = null; }
		});
	};
	this.UploadProcess = function (speed, postedLength, percent, times)
	{
	    this.FileLengthSvr = this.ATL.GetFileLenSvr();
		this.pPercent.text(percent);
		this.pProcess.css("width",percent);
		var str = "已上传:" + postedLength + " 速度:" + speed + "/S 剩余时间:" + times;
		this.pMsg.text(str);
	};

	this.svrDelete = function ()
	{
	    $.ajax({
	        type: "GET"
			, dataType: 'jsonp'
			, jsonp: "callback" //自定义的jsonp回调函数名称，默认为jQuery自动生成的随机函数名
			, url: _this.Config["UrlDel"]
			, data: { uid: _this.Fields["uid"], fid: _this.fid, time: new Date().getTime() }
			, success: function (msg)
			{
			}
			, error: function () { alert("文件-向服务器发送delete信息错误！" + req.responseText); }
			, complete: function (req, sta) { req = null; }
	    });
	};

	//启动下一个传输
	this.PostNext = function()
	{
		if (this.Manager.IsPostQueueFull()) return; //上传队列已满

		if (this.Manager.UploadQueue.length > 0)
		{
			var index = this.Manager.UploadQueue.shift();
			var obj = this.Manager.UploaderList[index];

			//空闲状态
			if (HttpUploaderState.Ready == obj.State)
			{
				obj.Post();
			}
		} //全部上传完成
		else
		{
			if (this.Manager.arrIdsErrStop.join("").length < 1)
			{
				//alert("所有文件上传完毕。");
			}
		}
	};
	
	//手动停止，一般在StopAll中调用
	this.StopManual = function()
	{
		if (HttpUploaderState.Posting == this.State)
		{
			this.pButton.text("续传");
			this.pMsg.text("传输已停止....");
			this.ATL.Stop();
			this.State = HttpUploaderState.Stop;
		}
	};
	
	//停止传输，一般在用户点击停止按钮时调用
	this.Stop = function()
	{
		if (HttpUploaderState.Ready == this.State)
		{
			this.pButton.text("续传");
			this.pMsg.text("传输已停止....");
			this.pBtnDel.show();
			this.State = HttpUploaderState.Stop;
			this.Manager.RemoveQueue(this.FileID);
			this.Manager.AppendUnUploadIds(this.FileID);//添加到未上传列表
			//this.PostNext();
			return;
		}
		
		this.pButton.text("续传");
		this.pMsg.text("传输已停止....");
		this.pBtnDel.show();
		this.ATL.Stop();
		this.State = HttpUploaderState.Stop;

		//从上传列表中删除
		this.Manager.RemoveUploadId(this.FileID);
		//添加到未上传列表
		this.Manager.AppendUnUploadIds(this.FileID);
		//传输下一个
		//this.PostNext();
	};
	
	//删除，一般在用户点击"删除"按钮时调用
	this.Delete = function()
	{
		this.svrDelete();//删除服务器信息
		this.Dispose();
		this.Manager.Delete(this.FileID);
	};

	//释放内存
	this.Dispose = function()
	{
		this.ATL.Dispose();
	};
	
	//快速上传完成，
	this.QuickComplete = function()
	{
	    this.event.postComplete(this);//biz event
		this.pButton.css("display","none");
		this.pProcess.css("width","100%");
		this.pPercent.text("100%");
		this.pMsg.text("服务器存在相同文件，快速上传成功。");
		this.Manager.arrFilesComplete.push(this);
		this.State = HttpUploaderState.Complete;
		//从上传列表中删除
		this.Manager.RemoveUploadId(this.FileID);
		//从未上传列表中删除
		this.Manager.RemoveUnUploadIds(this.FileID);
		this.Dispose();
		this.PostNext();
	};
}

//上传错误
function HttpUploader_Error(obj)
{
	if (null == obj.root)
	{
		obj.UploadError();
	}//文件夹
	else
	{ 
		obj.root.PostError();
	}
}

//上传完成，向服务器传送信息
function HttpUploader_Complete(obj/*HttpUploader对象或FolderUploader对象*/)
{
	if (null == obj.root)
	{
		obj.UploadComplete();
	}//文件夹上传任务
	else
	{
		obj.root.ItemPostComplete(obj);
	}
}

//传输进度。频率为每秒调用一次
function HttpUploader_Process(obj, speed, postedLength, percent, times)
{
	//是文件上传任务
	if (null == obj.root)
	{
		obj.UploadProcess(speed, postedLength, percent, times);
	}//是文件夹上传任务
	else
	{
		obj.root.ItemPostProcess(obj,speed, postedLength, percent, times);
	}
}

//服务器连接成功
function HttpUploader_Connected(obj)
{
	obj.pMsg.text("服务器连接成功");
}

//MD5计算中
function HttpUploader_MD5_Working(obj)
{
    if (null == obj.root)
    {
        var msg = "正在扫描本地文件，已完成：" + obj.ATL.GetMd5Percent() + "%";
        obj.pMsg.text(msg);
    }
}

//MD5计算完毕
function HttpUploader_MD5_Complete(obj)
{
    obj.MD5 = obj.ATL.GetMD5();
    obj.event.md5Complete(obj, obj.MD5);//biz event
    //在此处增加服务器验证代码。
    if(null == obj.root)
	    obj.pMsg.text("MD5计算完毕，开始连接服务器...");

	$.ajax({
		type: "GET"
		, dataType: 'jsonp'
		, jsonp: "callback" //自定义的jsonp回调函数名称，默认为jQuery自动生成的随机函数名
		, url: obj.Config["UrlCreate"]
		, data: { idSvr:obj.idSvr,md5: obj.MD5, uid: obj.Fields["uid"], pidSvr: obj.pidSvr, fileLength: obj.FileLength, fileSize: obj.FileSize, pathLocal: obj.PathLocal,pathSvr:obj.pathSvr,fdChild:obj.root!=null,time: new Date().getTime() }
		, success:function (msg)
		{
			var json = eval(msg)
			json = json[0];
			obj.fid = json.fid;
			obj.pathSvr = json.pathSvr;
			//obj.ResetFields();
			//服务器已存在相同文件，且已上传完成
			if ("1" == json.PostComplete)
			{
			    if (null == obj.root)
			    {
			        obj.QuickComplete();
			    }//文件夹项快速上传成功
			    else
			    {
			        obj.root.ItemQuickComplete(obj);
			    }
			} //服务器文件没有上传完成
			else
			{
			    obj.FileLengthSvr = json.PostedLength;
				//是文件
				if (null == obj.root)
				{
					obj.pProcess.css("width", json.PostedPercent);
					obj.pPercent.text(json.PostedPercent);
				}
				obj.Upload();
			}
		}
        , error: function (req, txt, err)
        {
        	alert("向服务器发送MD5信息错误！" + req.responseText);
        	obj.pMsg.text("向服务器发送MD5信息错误");
        	//文件夹项
        	if (obj.root)
        	{
        		obj.root.ItemMd5Error(obj);
        	} //文件项
        	else
        	{
        		obj.pButton.text("续传"); //如何通知文件夹？
        	}
        }
        , complete: function (req, sta) { req = null; }

	});
}

/*
	HUS_Leisure			=0	//空闲
	,HUS_Uploading		=1	//上传中 
	,HUS_Stop  			=2	//停止 
	,HUS_UploadComplete	=3	//传输完毕 
	,HUS_Error 			=4	//错误 
	,HUS_Connected 		=5	//服务器已连接
	,HUS_Md5Working		=6	//MD5计算中
	,HUS_Md5Complete	=7	//MD5计算完毕
*/
function HttpUploader_StateChanged(obj,state)
{
    switch (state)
	{
		case 0:
			break;
		case 1:
			break;
		case 2:
			break;
		case 3:
			HttpUploader_Complete(obj);
			break;
		case 4:
			HttpUploader_Error(obj);
			break;
		case 5:
			HttpUploader_Connected(obj);
			break;
		case 6:
			HttpUploader_MD5_Working(obj);
			break;
		case 7:
			HttpUploader_MD5_Complete(obj);
			break;
	}
}