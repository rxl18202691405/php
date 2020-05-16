function Ajax(){
	var aj=new Object();
		aj.recvType= 'TEXT' //TEXT XML JSON
		aj.targetUrl='';
		aj.sendString='';
		aj.async = true;
		aj.resultHandle=null;
		aj.beforeHandle=null;

	aj.createXMLHttpRequest=function(){
		var request=false;	
		//window对象中有XMLHttpRequest存在就是非IE，包括（IE7，IE8）
		if(window.XMLHttpRequest){
			request=new XMLHttpRequest();
			if(request.overrideMimeType){
				request.overrideMimeType("text/xml");
			}
		//window对象中有ActiveXObject属性存在就是IE
		}else if(window.ActiveXObject){
			var versions=['Microsoft.XMLHTTP', 'MSXML.XMLHTTP', 'Msxml2.XMLHTTP.7.0','Msxml2.XMLHTTP.6.0','Msxml2.XMLHTTP.5.0', 'Msxml2.XMLHTTP.4.0', 'MSXML2.XMLHTTP.3.0', 'MSXML2.XMLHTTP'];
			for(var i=0; i<versions.length; i++){
				try{
					request=new ActiveXObject(versions[i]);
					if(request){
						return request;
					}
				}catch(e){
					request=false;
				}
			}
		}
		return request;
	}
	aj.XMLHttpRequest=aj.createXMLHttpRequest();
	aj.processHandle=function(){
		if(aj.XMLHttpRequest.readyState == 1){
			( aj.beforeHandle != null ) ? aj.beforeHandle() : '';
		}else if(aj.XMLHttpRequest.readyState == 4){
			if(aj.XMLHttpRequest.status == 200){
				if(aj.recvType=="TEXT")
					aj.resultHandle(aj.XMLHttpRequest.responseText);
				else if(aj.recvType=="XML")
					aj.resultHandle(aj.XMLHttpRequest.responseXML);
				else if(aj.recvType=="JSON")
					aj.resultHandle(eval("("+aj.XMLHttpRequest.responseText+")"));
				
				/*请求完成后 重置状态*/
				aj.beforeHandle = null;
				aj.resultHandle = null;
				aj.recvType = 'TEXT';
				aj.targetUrl='';
				aj.sendString='';
				aj.async = true;
			}
		}
	}

	aj.get=function(d){
		aj.recvType= (d.dataType == undefined) ? aj.recvType : d.dataType.toUpperCase();
		aj.async = (d.async == undefined) ? aj.async : d.async;
		aj.targetUrl=d.url;
		var beforeHandle = d.beforeSend;
		var sendString = d.data;
		var resultHandle = d.success;
		sendString = sendString ? "?"+sendString : '';
		if(resultHandle!=undefined || beforeHandle!=undefined){
			aj.XMLHttpRequest.onreadystatechange=aj.processHandle;				
		}
		
		aj.resultHandle = (resultHandle != undefined) ? resultHandle : aj.resultHandle;
		aj.beforeHandle = (beforeHandle != undefined) ? beforeHandle : aj.beforeHandle;
		
		if(window.XMLHttpRequest){
			aj.XMLHttpRequest.open("get", aj.targetUrl+sendString, aj.async);
			aj.XMLHttpRequest.send(null);
		}else{
			aj.XMLHttpRequest.open("get", aj.targetUrl+sendString, aj.async);
			aj.XMLHttpRequest.send();
		}
	}
	aj.post=function(d){
		aj.recvType= (d.dataType == undefined) ? aj.recvType : d.dataType.toUpperCase();
		aj.async = (d.async == undefined) ? aj.async : d.async;
		aj.targetUrl=d.url;
		var beforeHandle = d.beforeSend;
		var sendString = d.data;
		var resultHandle = d.success;
		if(typeof(sendString)=="object"){
			var str="";
			for(var pro in sendString){
				str+=pro+"="+sendString[pro]+"&";	
			}
			aj.sendString=str.substr(0, str.length-1);
		}else{
			aj.sendString=sendString;
		}
		if(resultHandle!=undefined || beforeHandle!=undefined){
			aj.XMLHttpRequest.onreadystatechange=aj.processHandle;				
		}
		aj.resultHandle = (resultHandle != undefined) ? resultHandle : aj.resultHandle;
		aj.beforeHandle = (beforeHandle != undefined) ? beforeHandle : aj.beforeHandle;
		aj.XMLHttpRequest.open("post", aj.targetUrl, aj.async);
		aj.XMLHttpRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		aj.XMLHttpRequest.send(aj.sendString);
	}
	return aj;
}

var A = new Ajax();
//get  与 post 一样
A.post({
	url:URL,
	data:data,
	async:true/false,
	dataType:'json/text/xml',
	beforeSend:function(){
		//发送请求前  loading
	},
	success:function(r){
		//请求完成  回调			
	}
});