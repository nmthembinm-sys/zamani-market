document.querySelectorAll(".qty-btn").forEach(button => {

    button.addEventListener("click", function(){

        let cart_id = this.dataset.id;
        let action = this.dataset.action;

        fetch("update_cart.php",{
            method:"POST",
            headers:{
                "Content-Type":
                "application/x-www-form-urlencoded"
            },
            body:
            "cart_id=" + cart_id +
            "&action=" + action
        })
        .then(response => response.text())
        .then(data => {
            location.reload();
        });

    });

});