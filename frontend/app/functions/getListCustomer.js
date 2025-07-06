export default async function getListCustomer() {
  try {
    const res = await fetch('http://192.168.10.75:5000/api/sales/customer', {next: {revalidate: 24 * 3600}})

    if (!res.ok) {
      throw new Error('failed to fetch')
    }

    const result = await res.json()
    const { data } = result.payload
    return data
  } catch (error) {
    console.error(error);
    const data = [
      {
        tahun: null,
        bulan: null,
        namaCustomer: null,
        namaCustomer1: null,
        totalTargetQuantity: null,
        totalAktualQuantity: null,
        totalTargetUSD: null,
        totalAktualUSD: null,
      }
    ]
  }
}
