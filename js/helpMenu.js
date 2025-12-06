let open = false; 

function computeClosedPositions() {
    // remove transition because it messes up fetching current left valur
    $helpMenu.style.transition = "none";
    $help.style.transition = "none";

    $helpMenu.style.left = "";
    $help.style.left = "";
    // fetch computed style (based on %s using calc() function)
    closedLeft = parseFloat(getComputedStyle($helpMenu).left);
    closedHelpLeft = parseFloat(getComputedStyle($help).left);
    // unset transition value (restores to what's hardcoded in css)
    $helpMenu.style.transition = "";
    $help.style.transition = "";
}

window.onload = () => {
    $helpMenu = document.querySelector("aside");
    $help = document.querySelector(".help");
    computeClosedPositions();
};

document.querySelector(".help").onclick = () => {
    if (!open) {
        // if not open slide left based on the calculated closed position
        $helpMenu.style.left = (closedLeft - 430) + "px";
        $help.style.left = (closedHelpLeft - 429.1) + "px";
    } else {
        $helpMenu.style.left = closedLeft + "px";
        $help.style.left = closedHelpLeft + "px";
    }
    open = !open;
};

window.addEventListener("resize", () => {
    // close menu on resize
    open = false;
    computeClosedPositions();
    $helpMenu.style.left = closedLeft + "px";
    $help.style.left = closedHelpLeft + "px";
});
