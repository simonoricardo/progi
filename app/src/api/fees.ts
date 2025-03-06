const API_ENDPOINT = import.meta.env.VITE_API_URL

async function fetchFeesData(inputs: Record<string, string | number | null>) {
  const data = await fetch(`${API_ENDPOINT}/auction_fees/`, {
    method: 'POST',
    body: JSON.stringify(inputs),
  })
  return await data.json()
}

export { fetchFeesData }
