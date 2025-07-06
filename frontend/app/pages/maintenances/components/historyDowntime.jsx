import SideTitleCard from '@/app/components/cards/sideTitleCard'
import React from 'react'

async function getHistoryDowntime() {
  const res = await fetch('http://192.168.10.75:5000/api/maintenance/history-downtime', {next: {revalidate: 0}})
  if (!res.ok) {
    throw new Error('failed to fetch data')
  }
  return res.json()
}

export default async function HistoryDowntime() {
  const dataApi = await getHistoryDowntime()
  const data = dataApi.payload.data
  return (
    <>
      <div className="col h-full grid grid-cols-3 place-content-stretch gap-4">
        {data.map((element) => {
          const bg = parseFloat(element.percentDowntime) <= 70 ? 'bg-[#05A305]' : (parseFloat(element.percentDowntime) > 70) && (parseFloat(element.percentDowntime) <= 90) ? 'bg-[#FF9900]' : 'bg-[#FF0000]'
          const border = parseFloat(element.percentDowntime) <= 70 ? 'border-[#05A305]' : (parseFloat(element.percentDowntime) > 70) && (parseFloat(element.percentDowntime) <= 90) ? 'border-[#FF9900]' : 'border-[#FF0000]'
          const titleFont = 'text-[20px]'
          const valueFont = 'text-[40px]'
          return (
          <SideTitleCard
            key={element.percentDowntime}
            value={element.percentDowntime}
            title={element.bulanDowntime}
            tFont={titleFont} vFont={valueFont}
            bg={bg} border={border}
          />
        )})}
      </div>
    </>
  )
}

// import SideTitleCard from '@/app/components/cards/sideTitleCard';
// import React, { useState, useEffect } from 'react';

// async function getHistoryDowntime() {
//   const res = await fetch('http://192.168.10.75:5000/api/maintenance/history-downtime', {
//     method: 'GET',
//     headers: {
//       'Content-Type': 'application/json',
//     },
//   });

//   if (!res.ok) {
//     throw new Error('Gagal mengambil data');
//   }

//   return res.json();
// }

// export default function HistoryDowntime() {
//   const [data, setData] = useState([]);

//   useEffect(() => {
//     const fetchData = async () => {
//       try {
//         const dataApi = await getHistoryDowntime();
//         setData(dataApi.payload.data);
//       } catch (error) {
//         console.error('Error saat mengambil data:', error.message);
//       }
//     };

//     fetchData();
//   }, []);

//   return (
//     <>
//       <div className="col h-full grid grid-cols-3 place-content-stretch gap-4">
//         {data.map((element) => {
//           const bg =
//             parseFloat(element.percentDowntime) <= 70
//               ? 'bg-[#05A305]'
//               : parseFloat(element.percentDowntime) > 70 && parseFloat(element.percentDowntime) <= 90
//               ? 'bg-[#FF9900]'
//               : 'bg-[#FF0000]';
//           const border =
//             parseFloat(element.percentDowntime) <= 70
//               ? 'border-[#05A305]'
//               : parseFloat(element.percentDowntime) > 70 && parseFloat(element.percentDowntime) <= 90
//               ? 'border-[#FF9900]'
//               : 'border-[#FF0000]';
//           const titleFont = 'text-[20px]';
//           const valueFont = 'text-[40px]';

//           return (
//             <SideTitleCard
//               key={element.percentDowntime}
//               value={element.percentDowntime}
//               title={element.bulanDowntime}
//               tFont={titleFont}
//               vFont={valueFont}
//               bg={bg}
//               border={border}
//             />
//           );
//         })}
//       </div>
//     </>
//   );
// }
