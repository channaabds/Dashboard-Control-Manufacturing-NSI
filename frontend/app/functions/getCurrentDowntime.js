export default async function getCurrentDowntime() {
  try {
    const res = await fetch(
      "http://192.168.10.75:5000/api/maintenance/downtime",
      { next: { revalidate: 0 } }
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
