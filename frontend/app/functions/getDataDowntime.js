export default async function getDataDowntime(bulan) {
  try {
    const res = await fetch(
      `http://192.168.10.75:5000/api/maintenance/downtime/${bulan}`,
      { next: { revalidate: 30 * 24 * 3600 } }
    );

    if (!res.ok) {
      throw new Error("failed to fetch");
    }

    const result = await res.json();
    const { data } = result.payload;
    return data.percentDowntime;
  } catch (error) {
    console.error(error);
    return "0.00";
  }
}
