<div id="text-feature-sulat">
    <style>
    #text-feature-sulat {
        font-family: "Montserrat", sans-serif;
        font-optical-sizing: auto;
        font-weight: 900;
        font-style: normal;
        overflow: hidden;
        max-height: 400px;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #text-feature-sulat svg {
        width: 100%;
        height: 100%;
    }
    </style>

    <svg viewBox="0 0 1280 720">
        <mask id="maskLeft">
            <rect x="-50%" width="100%" height="100%" fill="#fff" />
        </mask>
        <mask id="maskRight">
            <rect x="50%" width="100%" height="100%" fill="#fff" />
        </mask>
        <g font-size="150">
            <g mask="url(#maskLeft)" class="left">
                <text y="150" fill="#00FFFF">OPEN AI +</text>
                <text y="280" fill="#FF00FF">VISION CLOUD</text>
                <text y="410" fill="#0080FF">= SULAT</text>
            </g>
            <g mask="url(#maskRight)" fill="#413c3cff" class="right">
                <text y="150">OPEN AI +</text>
                <text y="280">VISION CLOUD</text>
                <text y="410">= SULAT</text>
            </g>
        </g>
    </svg>
</div>

<script src="https://unpkg.com/gsap@3/dist/gsap.min.js"></script>
<script>
const container = document.querySelector('#text-feature-sulat');
const tl = gsap.timeline({
        defaults: {
            duration: 2,
            yoyo: true,
            ease: 'power2.inOut'
        }
    })
    .fromTo('#text-feature-sulat .left, #text-feature-sulat .right', {
        svgOrigin: '640 500',
        skewY: (i) => [-30, 15][i],
        scaleX: (i) => [0.6, 0.85][i],
        x: 200
    }, {
        skewY: (i) => [-15, 30][i],
        scaleX: (i) => [0.85, 0.6][i],
        x: -200
    })
    .play(.5);

const tl2 = gsap.timeline();

container.querySelectorAll('text').forEach((t, i) => {
    tl2.add(
        gsap.fromTo(t, {
            xPercent: -100,
            x: 700
        }, {
            duration: 1,
            xPercent: 0,
            x: 575,
            ease: 'sine.inOut'
        }), i % 3 * 0.2
    );
});

container.onpointermove = (e) => {
    tl.pause();
    tl2.pause();
    gsap.to([tl, tl2], {
        duration: 2,
        ease: 'power4',
        progress: e.x / window.innerWidth
    });
};
</script>