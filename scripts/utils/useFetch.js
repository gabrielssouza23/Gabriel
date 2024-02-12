export const useFetch = async (url, opts = {}, isText = false) => {
  const response = await fetch(url, opts);
  const data = !isText ? await response.json() : await response.text();

  return data;
};
