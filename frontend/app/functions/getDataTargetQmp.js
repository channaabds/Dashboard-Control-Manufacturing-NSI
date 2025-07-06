export default async function getDataTargetQmp() {
  try {
    const res = await fetch('http://192.168.10.75:5000/api/target/get-qmp', {next: {revalidate: 0}})

    if (!res.ok) {
      throw new Error('failed to fetch')
    }

    const result = await res.json()
    const { data } = result.payload
    return data
  } catch (error) {
    console.error(error)
    return 16000000
  }
}
