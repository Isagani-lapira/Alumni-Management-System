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

export async function postJSONFromURL(url, data) {
  const response = await fetch(url, {
    headers: {
      method: "POST",
      "Content-Type": "application/json",
      cache: "no-cache",
    },
    body: JSON.stringify(data),
  });
  const result = await response.json();
  return result;
}
