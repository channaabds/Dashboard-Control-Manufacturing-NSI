export default async function getDataMonthly(data) {
  try {
    const customer = data
    let targetUSD = 0;
    let aktualUSD = 0;

    customer.map((e) => {
      targetUSD += e.totalTargetUSD;
      aktualUSD += e.totalAktualUSD;
    });

    const result = (aktualUSD / targetUSD) * 100;
    if (isNaN(result)) {
      return 0
    }
    return result
  } catch (error) {
    console.error(error);
    return 0
  }
}
