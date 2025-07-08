const imageAvatar = document.getElementById("profil-photo");
const imagePreview = document.getElementById("imagePreview");
const imageInput = document.getElementById("imageUpload");
const cropArea = document.getElementById("crop-area");
const resizeHandle = cropArea.querySelector(".resize-handle");
const cropButton = document.getElementById("crop-button");
const canvasCircle = document.getElementById("result-circle");
const formProfil = document.getElementById("profil-form");

let isDragging = false,
isResizing = false;
let startX, startY, startWidth;

imageInput.addEventListener("change", function () {
const file = this.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function (e) {
      imagePreview.src = e.target.result;
      imagePreview.style.display = "block";
      cropArea.style.display = "block";
    };
    reader.readAsDataURL(file);
  }
});

cropArea.addEventListener("mousedown", (e) => {
  if (e.target === resizeHandle) return;
  isDragging = true;
  startX = e.clientX - cropArea.offsetLeft;
  startY = e.clientY - cropArea.offsetTop;
});

document.addEventListener("mousemove", (e) => {
  if (isDragging) {
    const x = e.clientX - startX;
    const y = e.clientY - startY;
    cropArea.style.left = `${x}px`;
    cropArea.style.top = `${y}px`;
  }
  if (isResizing) {
    const dx = e.clientX - startX;
    const newSize = Math.max(50, startWidth + dx);
    cropArea.style.width = `${newSize}px`;
    cropArea.style.height = `${newSize}px`;
  }
});

document.addEventListener("mouseup", () => {
  isDragging = false;
  isResizing = false;
});

resizeHandle.addEventListener("mousedown", (e) => {
  e.stopPropagation();
  isResizing = true;
  startX = e.clientX;
  startWidth = cropArea.offsetWidth;
});

cropButton.addEventListener("click", () => {
  const cropRect = cropArea.getBoundingClientRect();
  const imageRect = imagePreview.getBoundingClientRect();

  const scaleX = imagePreview.naturalWidth / imageRect.width;
  const scaleY = imagePreview.naturalHeight / imageRect.height;

  const x = (cropRect.left - imageRect.left) * scaleX;
  const y = (cropRect.top - imageRect.top) * scaleY;
  const size = cropRect.width * scaleX;

  const ctx = canvasCircle.getContext("2d");
  ctx.clearRect(0, 0, 156, 156);
  ctx.save();
  ctx.beginPath();
  ctx.arc(78, 78, 78, 0, Math.PI * 2);
  ctx.clip();
  ctx.drawImage(imagePreview, x, y, size, size, 0, 0, 156, 156);
  ctx.restore();

  canvasCircle.style.display = "block";
  imageAvatar.style.display = "none";

  // 🔁 Convertir le canvas en blob et soumettre comme fichier
  canvasCircle.toBlob(function (blob) {
    const file = new File([blob], "decoupe.webp", { type: "image/webp" });
    const dataTransfer = new DataTransfer();
    dataTransfer.items.add(file);
    imageInput.files = dataTransfer.files;

    formProfil.submit();
  }, "image/webp");
});
