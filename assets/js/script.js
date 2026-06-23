document.addEventListener("DOMContentLoaded", () => {

    /* Menu Mobile */

    const menuBtn = document.getElementById("menuBtn");
    const navMenu = document.getElementById("navMenu");
    const iconeMenu = document.getElementById("iconeMenu");

    menuBtn.addEventListener("click", (e) => {

        e.stopPropagation();

        navMenu.classList.toggle("aberto");

        if(navMenu.classList.contains("aberto")){

            iconeMenu.classList.remove("fa-bars");
            iconeMenu.classList.add("fa-xmark");

        }else{

            iconeMenu.classList.remove("fa-xmark");
            iconeMenu.classList.add("fa-bars");

        }

    });

    /* Menu Usuário */

    const usuarioBtn = document.getElementById("usuarioBtn");
    const usuarioDropdown = document.getElementById("usuarioDropdown");
    const setaDropdown = document.getElementById("setaDropdown");

    if(usuarioBtn){

        usuarioBtn.addEventListener("click",(e)=>{

            e.stopPropagation();

            usuarioDropdown.classList.toggle("ativo");
            setaDropdown.classList.toggle("girar");

        });

    }

    /* Fechar quando clica fora */

    document.addEventListener("click",(e)=>{

        if(navMenu.classList.contains("aberto")){

            if(
                !navMenu.contains(e.target) &&
                !menuBtn.contains(e.target)
            ){

                navMenu.classList.remove("aberto");

                iconeMenu.classList.remove("fa-xmark");
                iconeMenu.classList.add("fa-bars");

            }

        }

        if(usuarioBtn){

            if(
                !usuarioBtn.contains(e.target) &&
                !usuarioDropdown.contains(e.target)
            ){

                usuarioDropdown.classList.remove("ativo");
                setaDropdown.classList.remove("girar");

            }

        }

    });

    /* Pra fechar nos links */

    document.querySelectorAll(".nav-links a").forEach(link=>{

        link.addEventListener("click",()=>{

            navMenu.classList.remove("aberto");

            iconeMenu.classList.remove("fa-xmark");
            iconeMenu.classList.add("fa-bars");

        });

    });

});