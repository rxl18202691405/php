/*
第一个参数是表单的name
第二个参数是用户名的name
第三个参数是密码的name
第四个参数是验证码的name
第五个参数是电子邮件的name
第六个参数是标题的name
第七个参数是内容的name
第八个参数是分类的name
ps:调用函数的时候，所填参数必须按照这个顺序来写，如果不想验证某个name,参数为空(即:'')即可
*/


/*  调用示例：
	<script>
		var sub=document.getElementById('sub');          sub是提交按钮的id，要给submit按钮加上这个id
		sub.onclick=function(){                            
			return isEmpty('form','name','pwd');              form是表单的名字，要给<form action="" method="post">这句加上name="form"
		}
	</script>
*/

function isEmpty(form,name,pwd,imgcode,email,title,content,cat){     
		var my_form=document.forms[form];

		if(name!=false){
				var names=my_form.elements[name];
			if(names.value==""){   
				alert("用户名不能为空!");           
				return false; 
			}
		}		
		if(pwd!=false){
				var pwds=my_form.elements[pwd];
			if(pwds.value==""){   
				alert("密码不能为空!");          
				return false;   
			}  
		}
		if(imgcode!=false){
				var imgcodes=my_form.elements[imgcode];
			if(imgcodes.value==""){   
				alert("验证码未填写!");           
				return false; 
			}
		}		
		if(email!=false){
				var emails=my_form.elements[email];
			if(emails.value==""){   
				alert("邮箱不能为空!");           
				return false; 
			}
		}	
		if(title!=false){
				var titles=my_form.elements[title];
			if(titles.value==""){   
				alert("标题不能为空!");           
				return false; 
			}
		}
		if(content!=false){
				var contents=my_form.elements[content];
			if(contents.value==""){   
				alert("内容不能为空!");           
				return false; 
			}
		}		
			if(cat!=false){
				var cats=my_form.elements[cat];
			if(cats.value==""){   
				alert("分类名称不能为空!");           
				return false; 
			}
		}		
		return true;
}