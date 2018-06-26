/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function addAnswer(answer, question, url) {
    $.ajax({
        type: "POST",
        url: url,
        data: {answer_id: answer, question_id: question},
        success: function (data) {
            if (data == 1) {
                $('.alert-success').css("display", "block");
                if($('#list_answer').length > 0){
                 $.pjax.reload('#list_answer');   
                }                
                if($('#list_question').length > 0){
                 $.pjax.reload('#list_question');   
                }                
            }
        },
    });
}

function removeAnswer(answer, question, url) {
    $.ajax({
        type: "POST",
        url: url,
        data: {answer_id: answer, question_id: question},
        success: function (data) {
            if (data) {
                $('.alert-success').css("display", "block");
//                $.pjax.reload('#list_answer');
                if($('#list_answer').length > 0){
                 $.pjax.reload('#list_answer');   
                }                
                if($('#list_question').length > 0){
                 $.pjax.reload('#list_question');   
                }    
            }
        },
    });
}


function addNewAnswer(question_id, url) {
    var answer = $("[name='AnswerSearch[content]']").val();
    if (answer != '') {
        $.ajax({
            type: "POST",
            url: url,
            data: {answer: answer, question_id: question_id},
            success: function (data) {
                if (data) {
                    $('.alert-success').css("display", "block");
                    $.pjax.reload('#list_answer');
                }
            },
        });
    }
}

function addNewQuestion(answer_id, url) {
    var question = $("[name='QuestionSearch[content]']").val();
    if (question != '') {
        $.ajax({
            type: "POST",
            url: url,
            data: {question: question, answer_id: answer_id},
            success: function (data) {
                if (data) {
                    $('.alert-success').css("display", "block");
                    $.pjax.reload('#list_question');
                }
            },
        });
    }
}