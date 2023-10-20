export async function getJSONFromURL(url) {
  const response = await fetch(url, {
    headers: {
      method: "GET",
      "Content-Type": "application/json",
      cache: "no-cache",
    },
  });
  const result = await response.json();
  return result;
}

export async function postJSONFromURL(url, formData) {
  const response = await fetch(url, {
    method: "POST",
    body: formData,
  });
  const result = await response.json();
  return result;
}
