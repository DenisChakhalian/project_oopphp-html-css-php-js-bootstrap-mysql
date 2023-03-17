function scrollFunction() {
    const btnTop = document.getElementById("top");

    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        btnTop.style.display = "block";
    } else {
        btnTop.style.display = "none";
    }
}

window.addEventListener("load", function () {
    const commentTextarea = document.querySelector('#comment textarea');
    if (commentTextarea) {
        commentTextarea.addEventListener("input", () => {
            document.getElementById("counter").innerText = `${150 - commentTextarea.value.length}`;
        });
    }

    window.onscroll = function () {
        scrollFunction();
    };

    document.getElementById('edit').addEventListener('click', function (event) {
        console.log('click');
        const content = document.getElementById("content");
        console.log(content.style.display);
        const editor = document.getElementById("editor");
        content.style.display = 'none';
        editor.style.display = 'block';

    })

})
