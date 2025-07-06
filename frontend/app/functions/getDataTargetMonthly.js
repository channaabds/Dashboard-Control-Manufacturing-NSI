export default async function getDataTargetMonthly() {
  try {
    const res = await fetch('http://192.168.10.75:5000/api/target/get-monthly', {next: {revalidate: 0}})

    if (!res.ok) {
      throw new Error('failed to fetch')
    }

    const result = await res.json()
    const { data } = result.payload
    return data
  } catch (error) {
    console.error(error)
    return 1500000
  }
}
