<?php include "theme/homepage-header.php";?>
<style>
#group-intro * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}

#group-intro body {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background-color: #f5f5f5;
    overflow: hidden;
}

#group-intro .about-title {
    font-size: 7.5rem;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: -0.02em;
    position: absolute;
    top: 45px;
    left: 50%;
    transform: translateX(-50%);
    pointer-events: none;
    white-space: nowrap;
    font-family: "Arial Black", "Arial Bold", Arial, sans-serif;
    background: linear-gradient(to bottom,
            rgb(255 255 255 / 35%) 30%,
            rgb(255 255 255 / 0%) 76%);

    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

#group-intro .carousel-container {
    width: 100%;
    max-width: 1200px;
    margin: auto;
    height: 450px;
    position: relative;
    perspective: 1000px;
    margin-top: 80px;
}

#group-intro .carousel-track {
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    transform-style: preserve-3d;
    transition: transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

#group-intro .card {
    position: absolute;
    width: 280px;
    height: 380px;
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    transition: all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    cursor: pointer;
}

#group-intro .card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

#group-intro .card.center {
    z-index: 10;
    transform: scale(1.1) translateZ(0);
}

#group-intro .card.center img {
    filter: none;
}

#group-intro .card.left-2 {
    z-index: 1;
    transform: translateX(-400px) scale(0.8) translateZ(-300px);
    opacity: 0.7;
}

#group-intro .card.left-2 img {
    filter: grayscale(100%);
}

#group-intro .card.left-1 {
    z-index: 5;
    transform: translateX(-200px) scale(0.9) translateZ(-100px);
    opacity: 0.9;
}

#group-intro .card.left-1 img {
    filter: grayscale(100%);
}

#group-intro .card.right-1 {
    z-index: 5;
    transform: translateX(200px) scale(0.9) translateZ(-100px);
    opacity: 0.9;
}

#group-intro .card.right-1 img {
    filter: grayscale(100%);
}

#group-intro .card.right-2 {
    z-index: 1;
    transform: translateX(400px) scale(0.8) translateZ(-300px);
    opacity: 0.7;
}

#group-intro .card.right-2 img {
    filter: grayscale(100%);
}

#group-intro .card.hidden {
    opacity: 0;
    pointer-events: none;
}

#group-intro .member-info {
    text-align: center;
    margin-top: 40px;
    transition: all 0.5s ease-out;
}

#group-intro .member-name {
    color: rgba(219, 225, 238, 1);
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 10px;
    position: relative;
    display: inline-block;
}

#group-intro .member-name::before,
#group-intro .member-name::after {
    content: "";
    position: absolute;
    top: 100%;
    width: 100px;
    height: 2px;
    background: rgba(172, 176, 185, 1);
}

#group-intro .member-name::before {
    left: -120px;
}

#group-intro .member-name::after {
    right: -120px;
}

#group-intro .member-role {
    color: #cccccfff;
    font-size: 1.5rem;
    font-weight: 500;
    opacity: 0.8;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    padding: 10px 0;
    margin-top: -15px;
    position: relative;
}

#group-intro .dots {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 60px;
}

#group-intro .dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: rgba(8, 42, 123, 0.2);
    cursor: pointer;
    transition: all 0.3s ease;
}

#group-intro .dot.active {
    background: rgb(8, 42, 123);
    transform: scale(1.2);
}

#group-intro .nav-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(8, 42, 123, 0.6);
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 20;
    transition: all 0.3s ease;
    font-size: 1.5rem;
    border: none;
    outline: none;
    padding-bottom: 4px;
}

#group-intro .nav-arrow:hover {
    background: rgba(0, 0, 0, 0.8);
    transform: translateY(-50%) scale(1.1);
}

#group-intro .nav-arrow.left {
    left: 20px;
    padding-right: 3px;
}

#group-intro .nav-arrow.right {
    right: 20px;
    padding-left: 3px;
}

@media (max-width: 768px) {
    #group-intro .about-title {
        font-size: 4.5rem;
    }

    #group-intro .card {
        width: 200px;
        height: 280px;
    }

    #group-intro .card.left-2 {
        transform: translateX(-250px) scale(0.8) translateZ(-300px);
    }

    #group-intro .card.left-1 {
        transform: translateX(-120px) scale(0.9) translateZ(-100px);
    }

    #group-intro .card.right-1 {
        transform: translateX(120px) scale(0.9) translateZ(-100px);
    }

    #group-intro .card.right-2 {
        transform: translateX(250px) scale(0.8) translateZ(-300px);
    }

    #group-intro .member-name {
        font-size: 2rem;
    }

    #group-intro .member-role {
        font-size: 1.2rem;
    }

    #group-intro .member-name::before,
    #group-intro .member-name::after {
        width: 50px;
    }

    #group-intro .member-name::before {
        left: -70px;
    }

    #group-intro .member-name::after {
        right: -70px;
    }
}
</style>

<!-- START HERO -->
<section class="hero-section" id='group-intro'>

    <h1 class="about-title">OUR TEAM</h1>

    <div class="carousel-container">
        <button class="nav-arrow left">‹</button>
        <div class="carousel-track">
            <div class="card" data-index="0">
                <img src="theme\assets\images\members-img\G07_Falcunaya.png" alt="Team Member 1">
            </div>
            <div class="card" data-index="1">
                <img src="theme\assets\images\members-img\G08_Flores.jpeg" alt="Team Member 2">
            </div>
            <div class="card" data-index="2">
                <img src="theme\assets\images\members-img\G10_Garde.jpeg" alt="Team Member 3">
            </div>
            <div class="card" data-index="3">
                <img src="theme\assets\images\members-img\G20_Talavera.jpg" alt="Team Member 4">
            </div>
            <div class="card" data-index="4">
                <img src="theme\assets\images\members-img\G21_Untalan.jpeg" alt="Team Member 5">
            </div>
        </div>
        <button class="nav-arrow right">›</button>
    </div>

    <div class="member-info">
        <h2 class="member-name">David Kim</h2>
        <p class="member-role">Founder</p>
    </div>

    <div class="dots">
        <div class="dot active" data-index="0"></div>
        <div class="dot" data-index="1"></div>
        <div class="dot" data-index="2"></div>
        <div class="dot" data-index="3"></div>
        <div class="dot" data-index="4"></div>
    </div>

</section>
<!-- END HERO -->

<script>
const teamMembers = [{
        name: "Hannah Lorayne Falcunaya",
        role: "Member"
    },
    {
        name: "Lovevynia Kian Flores",
        role: "Member"
    },
    {
        name: "Joanna Paulene Garde",
        role: "Member"
    },
    {
        name: "Helene Marithe Talavera",
        role: "Member"
    },
    {
        name: "Lisa Anderson",
        role: "Member"
    },
    {
        name: "Luociea Lopez Untalan",
        role: "Member"
    }
];

const cards = document.querySelectorAll(".card");
const dots = document.querySelectorAll(".dot");
const memberName = document.querySelector(".member-name");
const memberRole = document.querySelector(".member-role");
const leftArrow = document.querySelector(".nav-arrow.left");
const rightArrow = document.querySelector(".nav-arrow.right");
let currentIndex = 0;
let isAnimating = false;

function updateCarousel(newIndex) {
    if (isAnimating) return;
    isAnimating = true;

    currentIndex = (newIndex + cards.length) % cards.length;

    cards.forEach((card, i) => {
        const offset = (i - currentIndex + cards.length) % cards.length;

        card.classList.remove(
            "center",
            "left-1",
            "left-2",
            "right-1",
            "right-2",
            "hidden"
        );

        if (offset === 0) {
            card.classList.add("center");
        } else if (offset === 1) {
            card.classList.add("right-1");
        } else if (offset === 2) {
            card.classList.add("right-2");
        } else if (offset === cards.length - 1) {
            card.classList.add("left-1");
        } else if (offset === cards.length - 2) {
            card.classList.add("left-2");
        } else {
            card.classList.add("hidden");
        }
    });

    dots.forEach((dot, i) => {
        dot.classList.toggle("active", i === currentIndex);
    });

    memberName.style.opacity = "0";
    memberRole.style.opacity = "0";

    setTimeout(() => {
        memberName.textContent = teamMembers[currentIndex].name;
        memberRole.textContent = teamMembers[currentIndex].role;
        memberName.style.opacity = "1";
        memberRole.style.opacity = "1";
    }, 300);

    setTimeout(() => {
        isAnimating = false;
    }, 800);
}

leftArrow.addEventListener("click", () => {
    updateCarousel(currentIndex - 1);
});

rightArrow.addEventListener("click", () => {
    updateCarousel(currentIndex + 1);
});

dots.forEach((dot, i) => {
    dot.addEventListener("click", () => {
        updateCarousel(i);
    });
});

cards.forEach((card, i) => {
    card.addEventListener("click", () => {
        updateCarousel(i);
    });
});

document.addEventListener("keydown", (e) => {
    if (e.key === "ArrowLeft") {
        updateCarousel(currentIndex - 1);
    } else if (e.key === "ArrowRight") {
        updateCarousel(currentIndex + 1);
    }
});

let touchStartX = 0;
let touchEndX = 0;

document.addEventListener("touchstart", (e) => {
    touchStartX = e.changedTouches[0].screenX;
});

document.addEventListener("touchend", (e) => {
    touchEndX = e.changedTouches[0].screenX;
    handleSwipe();
});

function handleSwipe() {
    const swipeThreshold = 50;
    const diff = touchStartX - touchEndX;

    if (Math.abs(diff) > swipeThreshold) {
        if (diff > 0) {
            updateCarousel(currentIndex + 1);
        } else {
            updateCarousel(currentIndex - 1);
        }
    }
}

updateCarousel(0);
</script>

<?php include "theme/homepage-footer.php";?>