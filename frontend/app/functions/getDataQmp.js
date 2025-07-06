export default async function getDataQmp() {
  try {
    const res = await fetch('http://192.168.10.75:5000/api/sales/get-actual', {next: {revalidate: 24 * 3600}})

    if (!res.ok) {
      throw new Error('failed to fetch')
    }

    const result = await res.json()
    const { data } = result.payload
    return data.totalUSDSales
  } catch (error) {
    console.error(error);
    return 0
  }
}
