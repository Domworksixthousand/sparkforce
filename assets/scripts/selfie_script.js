
//selfie sign up
  document.addEventListener("DOMContentLoaded", function(){
     const video = document.getElementById('webcam');
    const guide = document.getElementById('guide');
    const status = document.getElementById('status');
    const captureBtn = document.getElementById('captureBtn');
    const canvas = document.getElementById('snapshot');
    const selfieInput = document.getElementById('selfieInput');
    const previewImg = document.getElementById('preview');
    const camArea = document.getElementById('camArea');

    let isModelLoaded = false;
    let isFaceAligned = false;

    async function loadModels() {
        const MODEL_URL = 'https://vladmandic.github.io/face-api/model/';
        await faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL);
        isModelLoaded = true;
        startCamera();
    }

    async function startCamera() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: { width: 640, height: 480 } });
            video.srcObject = stream;
            status.innerText = "Align your face inside the guide.";
            video.addEventListener('play', onPlay);
        } catch (err) {
            status.innerText = "Camera Error: " + err.message;
        }
    }

    async function onPlay() {
        if (video.paused || video.ended || !isModelLoaded) return setTimeout(() => onPlay(), 100);

        const detection = await faceapi.detectSingleFace(video, new faceapi.TinyFaceDetectorOptions({ inputSize: 224, scoreThreshold: 0.5 }));

        const eyeLine = document.getElementById('eyeLine');
        const crossHairV = document.getElementById('crossHairV');

        if (detection) {
            const box = detection.box;
            const isCentered = Math.abs((box.x + box.width/2) - (video.videoWidth / 2)) < (video.videoWidth * 0.15);
            const isRightSize = box.width > (video.videoWidth * 0.25) && box.width < (video.videoWidth * 0.60);

            if (isCentered && isRightSize) {
                isFaceAligned = true;
                captureBtn.disabled = false;

                guide.classList.remove('border-dashed', 'border-rose-500', 'border-2');
                guide.classList.add('border-solid', 'border-emerald-500', 'border-4');

                if (eyeLine) {
                    eyeLine.classList.remove('border-rose-500/60', 'border-dashed');
                    eyeLine.classList.add('border-emerald-500/80', 'border-solid');
                }
                if (crossHairV) {
                    crossHairV.classList.remove('border-rose-500/40', 'border-dashed');
                    crossHairV.classList.add('border-emerald-500/60', 'border-solid');
                }

                status.innerText = "Perfect position, you can now click the capture button.";
                status.classList.remove('text-gray-500', 'text-rose-500');
                status.classList.add('text-emerald-500');
            } else {
                isFaceAligned = false;
                captureBtn.disabled = true;

                resetGuidesToRed(eyeLine, crossHairV);

                status.classList.remove('text-gray-500', 'text-emerald-500');
                status.classList.add('text-rose-500');
                status.innerText = !isCentered ? "Center and straighten your face." : (box.width <= (video.videoWidth * 0.25) ? "Move forward." : "Move backward.");
            }
        } else {
            isFaceAligned = false;
            captureBtn.disabled = true;

            resetGuidesToRed(eyeLine, crossHairV);

            status.innerText = "Look straight into the camera.";
            status.classList.remove('text-emerald-500', 'text-rose-500');
            status.classList.add('text-gray-500');
        }

        setTimeout(() => onPlay(), 200);
    }

    function resetGuidesToRed(eyeLine, crossHairV) {
        guide.classList.remove('border-solid', 'border-emerald-500', 'border-4');
        guide.classList.add('border-dashed', 'border-rose-500', 'border-2');

        if (eyeLine) {
            eyeLine.classList.remove('border-emerald-500/80', 'border-solid');
            eyeLine.classList.add('border-dashed', 'border-rose-500/60');
        }
        if (crossHairV) {
            crossHairV.classList.remove('border-emerald-500/60', 'border-solid');
            crossHairV.classList.add('border-dashed', 'border-rose-500/40');
        }
    }

    captureBtn.addEventListener('click', () => {
        // Retake flow
        if (captureBtn.innerText === "Capture again") {
            previewImg.style.display = "none";
            previewImg.src = "";
            video.style.display = "block";  
            captureBtn.innerText = "Take a Picture";
            status.innerText = "Align your face inside the guide.";
            status.style.color = "#555";

            selfieInput.value = "";
            return;
        }

        if (!isFaceAligned) return;

        const context = canvas.getContext('2d');
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        canvas.toBlob((blob) => {
            const file = new File([blob], "user_selfie.jpg", { type: "image/jpeg" });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);

            selfieInput.files = dataTransfer.files;

            console.log("File sa input:", selfieInput.files[0]);

            const imageUrl = URL.createObjectURL(blob);
            previewImg.src = imageUrl;
            previewImg.style.display = "block";
            video.style.display = "none";   

            status.innerText = "Successfully captured.";
            status.style.color = "#2ed573";

            captureBtn.innerText = "Capture again";

        }, 'image/jpeg', 0.9);
    });

    window.onload = loadModels;
   });