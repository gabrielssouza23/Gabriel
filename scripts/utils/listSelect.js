export default function listSelect(selector, options) {
  const select = document.querySelector(selector);
  options.map((option) => {
    const optionElement = document.createElement("option");
    optionElement.setAttribute("value", option.id);
    const optionText = document.createTextNode(option.name);
    optionElement.appendChild(optionText);
    select.appendChild(optionElement);
  });
}
