$(window).ready(function() {
  $(".side__menuItem").click(function() {
    let block = $(this).attr("data-target");
    if ($(block).css("display") == "block") {
      return;
    } else {
      $(".side__menuItem").removeClass("side__menuItem_active");
      $(this).addClass("side__menuItem_active");
      $(".content__slide").fadeOut("fast");
      setTimeout(() => {
        $(block).fadeIn("fast");
      }, 200);
    }
  });

  
  $(document).on("click", ".choose__item", function() {
    $(".depart__show").toggleClass("depart__show_active");
    $(this)
      .parent(".depart__choose")
      .slideToggle();
    if (
      $(this)
        .parent()
        .siblings("pic__select")
    ) {
      let choise = $(this).html();
      let type = $(this).attr("data-type");
      console.log(type);
      $(this)
        .parent()
        .siblings(".depart__show")
        .html(choise);

      $(".pic__input__select").val(`${type}`);
    } else {
      let choise = $(this).text();
      $(this)
        .parent()
        .siblings(".depart__show")
        .text(choise);
    }
  });

  $(".select__choise").click(function() {
    let tab = $(this).attr("data-target");
    $(".docs .docs__table").fadeOut("fast");
    setTimeout(() => {
      $(tab).fadeIn("fast");
    }, 200);
  });

  const animFunction = () => {
    return (animation = lottie.loadAnimation({
      container: document.getElementById("preloader"),
      renderer: "svg",
      loop: true,
      autoplay: true,
      path: "js/data.json"
    }));
  };

  animFunction();
  //$.fn.datepicker.defaults.format = "yyyy-mm-dd";
  $("#datepicker").datepicker({
    format: "yyyy-mm-dd"
  });
  $("#datepicker2").datepicker({
    format: "yyyy-mm-dd"
  });

  $(document).on("click", ".row__more", function() {
    console.log("open more main");
    if (
      $(this)
        .siblings(".row__moreBlock")
        .css("display") === "block"
    ) {
      $(this)
        .siblings(".row__moreBlock")
        .slideUp("fast");
      setTimeout(() => {
        $(this).removeClass("row__more_active");
      }, 200);
    } else {
      $(".row__moreBlock").slideUp("fast");
      $(".row__more").removeClass("row__more_active");
      $(this).addClass("row__more_active");
      $(this)
        .siblings(".row__moreBlock")
        .slideToggle("fast");
    }
  });
  $(document).keydown(function(eventObject) {
    if (eventObject.which == 27) {
      if ($(".overlay__modal_active").hasClass("new")) {
        $(".new")
          .find(".depart__show")
          .removeClass("depart__show_active");
        $(".new")
          .find(".depart__choose")
          .fadeOut();
      }
      $(".overlay__modal").removeClass("overlay__modal_active");
    }
  });

  $(document).on("click", ".moreBLock__item_edit", function() {
    $("#edit").css("display", "flex");
    setTimeout(() => {
      $("#edit").addClass("overlay__modal_active");
      $(".row__more_active").removeClass("row__more_active");
      $(".row__moreBlock").css("display", "none");
    }, 100);
  });

  $(".comment__btn").click(function() {
    let doneText = $(this).attr("data-text");
    $("#done")
      .find(".modal__text")
      .text(doneText);
    $("#done").css("display", "flex");
    setTimeout(() => {
      $("#done").addClass("overlay__modal_active");
    }, 100);
  });

 

  $(document).on("click", ".moreBLock__item_delete", function() {
    // console.log('data ===>',$(this).attr("data"));
    var id = $(this).attr("data");
    var event_name = $(this).attr("data-name");

    let why = $(this)
      .closest(".content__slide")
      .attr("id");

    switch (why) {
      case "docs":
        $("#delete")
          .find(".eventType")
          .text("РґРѕРєСѓРјРµРЅС‚");
        let docsName = $(this)
          .closest(".table__row")
          .find(".row__title")
          .text();
        $("#delete")
          .find(".eventName")
          .text(`"${docsName}"`);
        break;
      case "events":
        $("#delete")
          .find(".eventType")
          .text("событие");
        let eventName = $(this)
          .closest(".events__item")
          .find(".events__name")
          .text();
        console.log("event name ===>", eventName);
        $("#delete")
          .find(".eventName")
          .text(`"${event_name}"`);
          $("#delete")
            .find(".delete__btn")
            .val(`"${id}"`);

          ;

        break;
    }
    $("#delete").css("display", "flex");
    $("#delete")
      .find(".delete__btn")
      .attr({ data: `${id}` });

    console.log(why);
    setTimeout(() => {
      $("#delete").addClass("overlay__modal_active");
      $(".row__more_active").removeClass("row__more_active");
      $(".row__moreBlock").css("display", "none");
    }, 100);

    //console.log(st.state.monthData);
  });

   $(document).on("click", ".delete__btn", function() {
     var id = $(this).attr("data");

     //TODO сделать обращение к API / SQL
    //  $.ajax({
    //    method: "post",
    //    url: `http://localhost/api/query.php?act=delevent&id=${id}`
    //  }).then(res => {
    //    //console.log(res)
    //    if (res.status === 200) {
    //      if (res.data.status === 401) {
    //        alert("Ошибка автризации");
    //      } else if (res.data.status === 400) {
    //        alert("Незаполнено одно или несколько полей");
    //      } else {
    //        if (st._isMounted) {
    //          if (Object.keys(res.data).length > 0) {

    //           $(".event_state").attr('data-state', 'update')
             
    //          }
    //        }
    //      }
    //      return "succses";
    //    }
    //  });

    $(".event_state").val("update");

    
     $(".row__moreBlock").slideUp("fast");
     setTimeout(() => {
       $(".row__more").removeClass("row__more_active");
     }, 200);

     $(this)
       .parent()
       .parent()
       .parent()
       .removeClass("overlay__modal_active");
   });

   $(document).on("click", ".cancel__btn", function() {
      $(this)
        .parent()
        .parent()
        .parent()
        .removeClass("overlay__modal_active");
    });

  $(document).on("click", ".addEvent",  function() {
    let ev = $(this)
      .siblings(".slide__title")
      .text();
    ev = ev.trim();
    switch (ev) {
      case "События":
        $("#new").css("display", "flex");
        setTimeout(() => {
          $("#new").addClass("overlay__modal_active");
        }, 100);
        break;
      case "Документы":
        $("#edit").css("display", "flex");
        setTimeout(() => {
          $("#edit").addClass("overlay__modal_active");
        }, 100);
    }
  });
  // $(document).on("click", "#new .modal__btn", function() {
  //   console.log($("#new form").serialize());
  //   console.log($(".pic__select").attr("data-type"));
  //   var type = $(".pic__select").attr("data-type");

  //   var data = $("#new form").serialize();

   
  //   $.ajax({
  //     method: "post",
  //     url: `http://localhost/api/query.php?act=addevent&img=${type}&${data}`
  //   }).then(res => {
  //     //console.log(res)
  //     if (res.status === 200) {
  //       if (res.data.status === 401) {
  //         alert("Ошибка автризации");
  //       } else if (res.data.status === 400) {
  //         alert("Незаполнено одно или несколько полей");
  //       } else {
         
  //           if (Object.keys(res.data).length > 0) {
  //             $("#new form input[name=title]").val("");
  //             $("#new form input[name=type]").prop("checked", false);
  //             $("#new form input[name=date]").val("");
  //             $("#new form textarea[name=desc]").val("");
  //             $(".choose__item")
  //               .parent()
  //               .siblings(".depart__show")
  //               .html("<img src='./image/rose.svg' />");
  //             $(".choose__item")
  //               .parent()
  //               .siblings(".depart__show")
  //               .attr({ "data-type": `1` });

            
  //           }
          
  //       }
  //       return "succses";
  //     }
  //   });
  //   console.log(st);

  //   $(this)
  //     .parent()
  //     .parent()
  //     .parent()
  //     .removeClass("overlay__modal_active");
  // });

  $(".depart__show").click(function() {
    $(this).toggleClass("depart__show_active");
    $(this)
      .siblings(".depart__choose")
      .slideToggle();
  });

  

  $(".called__title, .called__subtitle").click(function() {
    $(this)
      .siblings(".called__content")
      .slideToggle();
  });

  $(".modal__close").click(function() {
    if (
      $(this)
        .parent()
        .parent()
        .hasClass("new")
    ) {
      console.log("sss");
      $(this)
        .parent()
        .parent()
        .find(".depart__show")
        .removeClass("depart__show_active");
      $(this)
        .parent()
        .parent()
        .find(".depart__choose")
        .fadeOut();
    }
    $(this)
      .parent()
      .parent()
      .removeClass("overlay__modal_active");
  });

  function clock() {
    var d = new Date();
    var day = d.getDate();
    var hrs = d.getHours();
    var min = d.getMinutes();
    var sec = d.getSeconds();

    var mnt = new Array(
      "января",
      "февраля",
      "марта",
      "апреля",
      "мая",
      "июня",
      "июля",
      "августа",
      "сентября",
      "октября",
      "ноября",
      "декабря"
    );

    if (day <= 9) day = "0" + day;
    if (hrs <= 9) hrs = "0" + hrs;
    if (min <= 9) min = "0" + min;
    if (sec <= 9) sec = "0" + sec;

    $("#time").html(
      day +
        "&nbsp" +
        mnt[d.getMonth()] +
        " " +
        d.getFullYear() +
        " <br>" +
        hrs +
        ":" +
        min
    );
  }
  setInterval(clock(), 1000);

  $("#gosnomer").on("keyup", function() {
    // вешаем обработчик на инпут
    console.log("sss");
    let val = $(this).val(); // получаем значение в переменную
    if (val.length === 6) {
      // нам нужно, чтобы значение было шесть символов в длину
      if (
        val.match(
          /^(а|в|е|к|м|н|о|р|с|т|у|х){1}[0-9]{3}(а|в|е|к|м|н|о|р|с|т|у|х){2}$/gi
        )
      ) {
        // запускаем проверку по нашей регулярке
        $(this).css("color", "green"); // красим текст инпута в зелёный, если это гос. номер по ГОСТу
      } else {
        $(this).css("color", "red"); // красим текст в красный, если нет
      }
    } else $(this).css("color", "inherit"); // если длина значение не шесть символов, то возращаем дефолтный цвет
  });
  $(".hint").on("focus", function() {
    $(".hint__block").css("opacity", "1");
    setTimeout(() => {
      $(".hint__block").css("opacity", "0");
    }, 7000);
  });
});
