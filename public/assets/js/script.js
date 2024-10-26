$(function show() {
    // console.log("jdjdjdj");
    //show create box
    $("#createAccountBt").click(function (e) {
        $(".createAccountBox").toggleClass("d-none");
    });
    $(".closeBt").click(function (e) {
        $(".createAccountBox").addClass("d-none");
    })

})