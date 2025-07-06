/* eslint-disable array-callback-return */
const downtimeToSeconds = require("./downtimeToSeconds");
const addingDowntime = require("./addingDowntime");

module.exports = function currentDowntimeFormater(array) {
  const data = array.map((element) => {
    // Cek status_aktifitas dan pilih downtime yang sesuai
    const downtime =
      element.status_aktifitas === "Running"
        ? element.total_monthly_downtime // Ambil total_monthly_downtime jika status Running
        : addingDowntime(
            element.current_monthly_downtime,
            element.total_monthly_downtime
          ); // Jika Stop, tambahkan current_monthly_downtime dan total_monthly_downtime

    return downtime;
  });

  let totalDowntime = "0:0:0:0"; // Inisialisasi total downtime dengan nilai 0
  data.map((element) => {
    totalDowntime = addingDowntime(totalDowntime, element); // Jumlahkan downtime setiap elemen
  });

  const downtimeInSeconds = downtimeToSeconds(totalDowntime); // Konversi ke detik

  return {
    totalDowntime, // Total downtime dalam format jam:menit:detik:mili
    totalDowntimeInSeconds: downtimeInSeconds, // Total downtime dalam detik
  };
};
