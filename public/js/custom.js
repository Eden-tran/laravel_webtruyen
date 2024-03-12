//Somehow select the element
// Create Slug
function toSlug(str) {
    // Chuyển hết sang chữ thường
    str = str.toLowerCase();
    // xóa dấu
    str = str
        .normalize("NFD") // chuyển chuỗi sang unicode tổ hợp
        .replace(/[\u0300-\u036f]/g, ""); // xóa các ký tự dấu sau khi tách tổ hợp
    // Thay ký tự đĐ
    str = str.replace(/[đĐ]/g, "d");
    // Xóa ký tự đặc biệt
    str = str.replace(/([^0-9a-z-\s])/g, "");
    // Xóa khoảng trắng thay bằng ký tự -
    str = str.replace(/(\s+)/g, "-");
    // Xóa ký tự - liên t
    str = str.replace(/-+/g, "-");
    // xóa phần dư - ở đầu & cuối
    str = str.replace(/^-+|-+$/g, "");
    // return
    return str;
}
function createSlug() {
    var slugArr = [
        ["txtMangaName", "txtMangaSlug"],
        ["txtCateName", "txtCateSlug"],
        ["txtChapterName", "txtChapterSlug"],
    ];
    for (let index = 0; index < slugArr.length; index++) {
        var Name = document.getElementById(slugArr[index][0]);
        var Slug = document.getElementById(slugArr[index][1]);
        if (Name && Slug) {
            break;
        }
    }
    if (Name && Slug) {
        Name.onkeyup = function () {
            Slug.value = toSlug(this.value);
        };
    }
}
createSlug();
// End Create Slug
// Preview Cover
var imgMangaCover = document.getElementById("imgCover");
if (imgMangaCover) {
    imgMangaCover.onchange = function (event) {
        if (event.target.files.length > 0) {
            var src = URL.createObjectURL(event.target.files[0]);
            var preview = document.getElementById("coverPreview");
            preview.src = src;
            console.log(preview.src);
            preview.setAttribute("style", "display:block !important");
        }
    };
}
// End Preview Cover
// Preview manga pages
// var inpPages = document.getElementById("inpPages");
// var pages = [];
// var removeBtn = document.getElementsByClassName("remove-btn");
// if (inpPages) {
//     inpPages.onchange = function (event) {
//         pages_select();
//     };
//     inpPages.value = "";
// }

// function pages_select() {
//     var inpPages = document.getElementById("inpPages").files;
//     var pagesPreview = document.getElementById("pagesPreview");

//     for (let index = 0; index < inpPages.length; index++) {
//         pages.push({
//             name: inpPages[index].name,
//             url: URL.createObjectURL(inpPages[index]),
//             file: inpPages[index],
//         });
//     }
//     pagesPreview.innerHTML = pages_show();
// }
// function pages_delete(e) {
//     pages.splice(e, 1);
//     pagesPreview.innerHTML = pages_show();
// }

// function pages_show() {
//     var content = "";
//     pages.forEach(function (v, index) {
//         content += `<div class="col-xl-3 col-lg-4 col-sm-6 py-2 text-center mangaPages" style="position: relative" draggable="true">
//         <img style="width:200px;height:200px"
//             src="${v.url}" class="rounded mx-auto d-block"
//             alt="...">
//         <span class="text-center">${index + 1}</span>
//         <button type='button' class="remove-btn" onclick="pages_delete(${pages.indexOf(
//             v
//         )})"> <i class="fas fa-times"></i>
//         </button>
//     </div> `;
//     });
//     return content;
// }
// // dragable img
// var pagesPreview = document.getElementById("pagesPreview");
// var items = pagesPreview.getElementsByClassName("mangaPages");
// items.forEach((item) => {
//     item.addEventListener("dragstart", () => {
//         // Adding dragging class to item after a delay
//         setTimeout(() => item.classList.add("dragging"), 0);
//     });
//     // Removing dragging class from item on dragend event
//     item.addEventListener("dragend", () => item.classList.remove("dragging"));
// });

// const initSortableList = (e) => {
//     e.preventDefault();
//     const draggingItem = document.querySelector(".dragging");
//     // Getting all items except currently dragging and making array of them
//     let siblings = [...pagesPreview.querySelectorAll(".item:not(.dragging)")];

//     // Finding the sibling after which the dragging item should be placed
//     let nextSibling = siblings.find((sibling) => {
//         return e.clientY <= sibling.offsetTop + sibling.offsetHeight / 2;
//     });

//     // Inserting the dragging item before the found sibling
//     pagesPreview.insertBefore(draggingItem, nextSibling);
// };

// pagesPreview.addEventListener("dragover", initSortableList);
// pagesPreview.addEventListener("dragenter", (e) => e.preventDefault());
