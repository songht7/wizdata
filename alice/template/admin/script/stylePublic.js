/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(function () {
    $(".first").click(function () {
        $(".first").removeClass('mainRed').next().hide();
        $(this).addClass('mainRed').next().show();
        $(".second").removeClass('mainRed');
    });
    $(".second").click(function () {
        $(".second").removeClass('mainRed');
        $(this).addClass('mainRed');
    });
});