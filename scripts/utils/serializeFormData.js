export default function serializeFormData(form) {
  var formData = new FormData(form);
  var serializedData = {};
  for (var [name, value] of formData) {
    if (serializedData[name]) {
      if (!Array.isArray(serializedData[name])) {
        serializedData[name] = [serializedData[name]];
      }
      serializedData[name].push(value);
    } else {
      serializedData[name] = value;
    }
  }

  return serializedData;
}
