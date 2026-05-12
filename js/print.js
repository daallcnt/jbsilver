function printDiv() {

 bV = parseInt(navigator.appVersion);

 if (bV >= 4) window.print();

 }

var tempBody;

window.onbeforeprint = function() {

  tempBody = document.body.innerHTML;

  document.body.innerHTML = d1.innerHTML;

}

window.onafterprint = function() {

  document.body.innerHTML = tempBody;

}


function lookAhead(id) {

        if(typeof(document.all[id])=='object') {
                myText='';

                if(typeof(newObj)=='object') newObj.close();

                newObj=window.open("","newWin","menubar=no, scrollbars=yes, status=no, toolbar=no, resizable=yes, width=700, height=600, top=50, left=50");

                if(newObj != null) {

                        myText += "<html><head><title>미리보기 테스트 입니다.</title><link rel='stylesheet' href='../style_ess.css' type='text/css'></head><body>";
                        myText += document.all[id].innerHTML;
                        myText += "</body></html>";

                        newObj.document.write(myText);
                }
        }
        else alert('미리보기 영역이 정의되어 있지 않습니다.');
}

// 탭메뉴
$(function () {	
	tab('#tab',0);	
});

function tab(e, num){
    var menu = $(e).children();
    var con = $(e+'_con').children();
    var select = $(menu).eq(num);
    var i = num;

    select.addClass('on');
    con.eq(num).show();

    menu.click(function(){
        if(select!==null){
            select.removeClass("on");
            con.eq(i).hide();
        }

        select = $(this);	
        i = $(this).index();

        select.addClass('on');
        con.eq(i).show();
    });
}



// 리로드 없이 새로고침   <a href="javascript:menuClicks('url','#main3');">
var menuClicks = function(url,taget){
	if(url == '/'){
		location.reload(true);
		return;
	}
	
	$.ajax({
		type: 'POST',
		url: url,
		async:false,
		data: "",
		contentType:"application/x-www-form-urlencoded; charset=UTF-8",
		success: function(data) {
			$(taget).html(data);
			
			if(isMenuHide) menuOff();
		},
		error: function(request, status, error) {
			alert(error);
		}
	});
};


	function info_print() {

		var initBody = document.body.innerHTML;
		window.onbeforeprint = function () {
			document.body.innerHTML = document.getElementById("container").innerHTML;
		}

		window.onafterprint = function () {
			document.body.innerHTML = initBody;
		}
		window.print();
	}