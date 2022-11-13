$(".tabs-list .tabs-menu__item").on("click", function() {
    if(!$(this).hasClass("active")) {
        $(this).parent().find(".tabs-menu__item").removeClass("active");
        $(this).addClass("active");
        const index = $(this).hasClass("tabs-menu__item-second") ? 1 : 0;
        $(".tabs-menu .tabs-menu__item").removeClass("active").eq(index).addClass("active");
        $(this).parent().find(".tabs-item").removeClass("active").slideUp(200);
        $(this).next().addClass("active").slideDown(200);
    }
})

$(".tabs-menu .tabs-menu__item").on("click", function() {
    if(!$(this).hasClass("active")) {
        $(this).parent().find(".tabs-menu__item").removeClass("active");
        $(this).addClass("active");
        $(".tabs-list .tabs-menu__item").removeClass("active").eq($(this).index()).addClass("active");
        $(".tabs-list .tabs-item").removeClass("active").fadeOut(10).eq($(this).index()).addClass("active").fadeIn(10);
    }
})

$.ajax({
    method: "POST",
    url: "/php/data.php",
    data: {
        type: "get-tabs-data"
    },
    success: function (data) {
        if (data !== "") {
            let res = JSON.parse(data);
            initCard(res);
        }
    }
});

function initCard(data) {
    let col = $(window).width() >= 568 ? 2 : 1;
    data.forEach((el, i) => {
        $(".tabs-menu .tabs-menu__item").eq(i).text(el.tasbsName);
        $(".tabs-list .tabs-menu__item").eq(i).text(el.tasbsName);

        const tab = $(".tabs-list .tabs-item").eq(i);
        const img = el.image.slice(0, -4);
        tab.find("source").attr("srcset", `${img}.webp`);
        tab.find(".image").attr("src", `${img}.jpg`).attr("alt", el.title);

        tab.find(".tabs-item__title").text(el.title);
        const desc = el.description.reduce((a, b) => a += `<p class="tabs-item__text">${b}</p>`, "");
        tab.find(".tabs-item__desc").append(desc);

        renderSpec(getOrder(el.spec, col), tab);
    });

    $(window).on("resize", () => {
        if ($(window).width() >= 568 && col == 1) {
            col = 2;
            data.forEach((el, i) => renderSpec(getOrder(el.spec, col), $(".tabs-list .tabs-item").eq(i)));
        } else if ($(window).width() < 568 && col == 2) {
            col = 1;
            data.forEach((el, i) => renderSpec(getOrder(el.spec, col), $(".tabs-list .tabs-item").eq(i)));
        }
    });
}

function getOrder(arr, col) {
    let res = [];
    if (col == 2) {
        const rows = Math.ceil(arr.length / 2);
        for (let k = 0; k < rows; k++) {
            res.push(arr[k]);
            if (res[res.length - 1].icon == "") res[res.length - 1].icon = k + 1;
            if (arr[k + rows]) {
                res.push(arr[k + rows]);
                if (res[res.length - 1].icon == "") res[res.length - 1].icon = k + 1 + rows;
            }
        }
    } else {
        arr.forEach((el, i) => {
            res.push(el);
            if (res[res.length - 1].icon == "") res[res.length - 1].icon = i + 1;
        });
    }

    return res;
}

function renderSpec(spec, tab) {
    tab.find(".tabs-item__spec").empty();
    let res ="";
    spec.forEach(el => {
        if (!isNaN(parseFloat(el.icon)) && isFinite(el.icon)) {
            res += `<div class="tabs-item__spec-item">
                        <div class="tabs-item__spec-icon">${el.icon}.</div>
                        ${el.text}
                    </div>`;
        } else {
            res += `<div class="tabs-item__spec-item tabs-item__spec-item_pad">
                        <svg class="icon tabs-item__spec-icon">
                            <use xlink:href="/img/svg/icons.svg#${el.icon}"></use>
                        </svg>
                        ${el.text}
                    </div>`;
        }
    });

    tab.find(".tabs-item__spec").append(res);
}
