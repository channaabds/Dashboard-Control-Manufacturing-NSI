const { DateTime } = require("luxon");
const mysql = require("../services/database/mysql");

module.exports = {
  getCurrentDowntime(callback) {
    try {
      mysql.query(
        `SELECT status_aktifitas, current_monthly_downtime, total_monthly_downtime,
        DATE_FORMAT(NOW(), '%m/%y') as bulan_downtime
        FROM machine_repairs WHERE MONTH(downtime_month) = MONTH(now())
        AND YEAR(downtime_month) = YEAR(now())`,
        callback
      );
    } catch (error) {
      console.error(error);
    }
  },
  // getBeforeDowntime(bulan, calback) {
  //   try {
  //     const dt = DateTime.now();
  //     // let month = bulan;
  //     let { year } = dt;
  //     const getMonth = dt.month;
  //     console.log(year - 1);
  //     if (getMonth === 1) {
  //       year -= year;
  //       mysql.query(`SELECT total_downtime AS totalDowntime, bulan_downtime FROM total_downtimes
  //         WHERE MONTH(bulan_downtime) = 11
  //         AND YEAR(bulan_downtime) = ${year}`, calback);
  //     } else if (getMonth === 2) {
  //       year -= year;
  //       mysql.query(`SELECT total_downtime AS totalDowntime, bulan_downtime FROM total_downtimes
  //         WHERE MONTH(bulan_downtime) = 12
  //         AND YEAR(bulan_downtime) = ${year}`, calback);
  //     } else {
  //     // mysql.query(`SELECT total_downtime AS totalDowntime, bulan_downtime FROM total_downtimes
  //     //   WHERE MONTH(bulan_downtime) = MONTH(now())-${bulan}
  //     //   AND YEAR(bulan_downtime) = YEAR(now())`, calback);
  //       mysql.query(`SELECT total_downtime AS totalDowntime, bulan_downtime FROM total_downtimes
  //         WHERE MONTH(bulan_downtime) = MONTH(now())-${bulan}
  //         AND YEAR(bulan_downtime) = ${year}`, calback);
  //     }
  //   } catch (error) {
  //     console.error(error);
  //   }
  // },
  getBeforeDowntime(b, callback) {
    try {
      const dt = DateTime.now();
      let { year, month } = dt;
      const bulan = parseInt(b);

      if (month === 1 || month === 2) {
        if (month === 1) {
          if (bulan === 1) {
            year -= 1;
            month = 12;
          }
          if (bulan === 2) {
            year -= 1;
            month = 11;
          }
        }
        if (month === 2) {
          if (bulan === 1) {
            month = 1;
          }
          if (bulan === 2) {
            year -= 1;
            month = 12;
          }
        }
      } else {
        month -= bulan;
      }

      mysql.query(
        `SELECT total_downtime AS totalDowntime, bulan_downtime FROM total_downtimes
        WHERE MONTH(bulan_downtime) = ${month}
        AND YEAR(bulan_downtime) = ${year}`,
        callback
      );
    } catch (error) {
      console.error(error);
    }
  },
  // getBeforeDowntime(bulan, callback) {
  //   try {
  //     const dt = DateTime.now();
  //     let { year, month } = dt;

  //     if (month === 1 && bulan === 1) {
  //       year -= 1;
  //       month = 12;
  //     }

  //     if (month === 1 && bulan === 2) {
  //       year -= 1;
  //       month = 11;
  //     }

  //     if (month === 2 && bulan === 1) {
  //       month -= 1;
  //     }

  //     if (month === 2 && bulan === 2) {
  //       year -= 1;
  //       month = 12;
  //     }

  //     if (month !== 1 && month !== 2 && bulan === 1) {
  //       month -= 1;
  //     }

  //     if (month !== 1 && month !== 2 && bulan === 2) {
  //       month -= 2;
  //     }

  //     mysql.query(`SELECT total_downtime AS totalDowntime, bulan_downtime FROM total_downtimes
  //       WHERE MONTH(bulan_downtime) = ${month}
  //       AND YEAR(bulan_downtime) = ${year}`, callback);
  //   } catch (error) {
  //     console.error(error);
  //   }
  // },

  // getBeforeTwoMonths(bulan, calback) {
  //   try {
  //     mysql.query(`SELECT total_downtime AS totalDowntime, bulan_downtime FROM total_downtimes
  //       WHERE bulan_downtime >= DATE_SUB(NOW(), INTERVAL ${parseInt(bulan, 10) + 1} MONTH)
  //       AND MONTH(bulan_downtime) != MONTH(now()) ORDER BY bulan_downtime DESC`, calback);
  //   } catch (error) {
  //     console.error(error);
  //   }
  // },

  // getBeforeTwoMonths(bulan, callback) {
  //   try {
  //     let query = `SELECT total_downtime AS totalDowntime, bulan_downtime FROM total_downtimes
  //       WHERE MONTH(bulan_downtime) = MONTH(DATE_SUB(NOW(), INTERVAL ${parseInt(bulan, 10) + 2} MONTH))
  //       AND YEAR(bulan_downtime) = YEAR(DATE_SUB(NOW(), INTERVAL ${parseInt(bulan, 10) + 2} MONTH))
  //       ORDER BY bulan_downtime DESC`;

  //     if (new Date().getMonth() === 0) {
  //       // Jika bulan saat ini adalah Januari, ambil data dari Desember tahun sebelumnya
  //       query = `SELECT total_downtime AS totalDowntime, bulan_downtime FROM total_downtimes
  //         WHERE MONTH(bulan_downtime) = 12
  //         AND YEAR(bulan_downtime) = YEAR(DATE_SUB(NOW(), INTERVAL ${parseInt(bulan, 10) + 2} MONTH)) - 1
  //         ORDER BY bulan_downtime DESC`;
  //     }

  //     mysql.query(query, callback);
  //   } catch (error) {
  //     console.error(error);
  //   }
  // },
  // getBeforeTwoMonths(bulan, callback) {
  //   try {
  //     const dt = DateTime.now();
  //     let { year, month } = dt;
  //     let query;

  //     if (month !== 2 || month !== 1) {
  //       month -= 2;
  //       query = `SELECT total_downtime AS totalDowntime, bulan_downtime FROM total_downtimes
  //                 WHERE MONTH(bulan_downtime) = ${month}
  //                 AND YEAR(bulan_downtime) = ${year}`;
  //     }

  //     if (month === 1) {
  //       month = 11;
  //       year -= 1;
  //       query = `SELECT total_downtime AS totalDowntime, bulan_downtime FROM total_downtimes
  //                 WHERE MONTH(bulan_downtime) = ${month}
  //                 AND YEAR(bulan_downtime) = ${year}`;
  //     }

  //     if (month === 1) {
  //       month = 12;
  //       year -= 1;
  //       query = `SELECT total_downtime AS totalDowntime, bulan_downtime FROM total_downtimes
  //                 WHERE MONTH(bulan_downtime) = ${month}
  //                 AND YEAR(bulan_downtime) = ${year}`;
  //     }

  //     mysql.query(query, callback);
  //   } catch (error) {
  //     console.error(error);
  //   }
  // },

  getBeforeTwoMonths(bulan, callback) {
    try {
      let query;
      if (new Date().getMonth() === 0) {
        `SELECT total_downtime AS totalDowntime, bulan_downtime FROM total_downtimes
        WHERE MONTH(bulan_downtime) != MONTH(NOW())
        AND YEAR(bulan_downtime) = YEAR(DATE_SUB(NOW(), INTERVAL 2 MONTH))
        ORDER BY bulan_downtime DESC`;
      } else if (new Date().getMonth() === 0) {
        // Jika bulan saat ini adalah Januari, ambil data dari Desember tahun sebelumnya
        query = `SELECT total_downtime AS totalDowntime, bulan_downtime FROM total_downtimes
          WHERE MONTH(bulan_downtime) = 12
          AND YEAR(bulan_downtime) = YEAR(DATE_SUB(NOW(), INTERVAL 2 MONTH)) - 1
          ORDER BY bulan_downtime DESC`;
      }

      mysql.query(query, callback);
    } catch (error) {
      console.error(error);
    }
  },

  getCurrentMachines(calback) {
    try {
      mysql.query(
        `SELECT machine_repairs.id, machines.no_mesin AS noMesin,
        machine_repairs.pic, machine_repairs.status_aktifitas,
        machine_repairs.status_mesin, machine_repairs.tgl_kerusakan,
        machine_repairs.current_downtime, machine_repairs.total_downtime
        FROM machine_repairs JOIN machines ON
        machine_repairs.mesin_id = machines.id
        WHERE status_mesin != 'OK Repair (Finish)' AND status_aktifitas = 'Stop' AND keterangan IS NULL`,
        calback
      );
    } catch (error) {
      console.error(error);
    }
  },

  getHistoryDowntime(callback) {
    try {
      mysql.query(
        `SELECT total_downtime AS totalDowntime, bulan_downtime FROM total_downtimes
        WHERE bulan_downtime >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
        AND MONTH(bulan_downtime) != MONTH(now()) ORDER BY bulan_downtime DESC`,
        callback
      );
    } catch (error) {
      console.error(error);
    }
  },

  // getHistoryDowntime(callback) {
  //   try {
  //     let query ;
  //     if (new Date().getMonth() === 0){ `SELECT total_downtime AS totalDowntime, bulan_downtime FROM total_downtimes
  //       WHERE MONTH(bulan_downtime) != MONTH(NOW())
  //       AND YEAR(bulan_downtime) = YEAR(DATE_SUB(NOW(), INTERVAL 1 MONTH))
  //       ORDER BY bulan_downtime DESC`;}

  //     else if (new Date().getMonth() === 0) {
  //       // Jika bulan saat ini adalah Januari, ambil data dari Desember tahun sebelumnya
  //       query = `SELECT total_downtime AS totalDowntime, bulan_downtime FROM total_downtimes
  //         WHERE MONTH(bulan_downtime) = 12
  //         AND YEAR(bulan_downtime) = YEAR(DATE_SUB(NOW(), INTERVAL 1 MONTH)) - 1
  //         ORDER BY bulan_downtime DESC`;
  //     }

  //     mysql.query(query, callback);
  //   } catch (error) {
  //     console.error(error);
  //   }
  // },
};
