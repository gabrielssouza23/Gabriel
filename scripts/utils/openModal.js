export default function openModal(idModal, abrir) {
  const modal = document.getElementById(idModal);
  if (!abrir) modal.style.display = "none";
  else modal.style.display = "block";
}
