import ChartDowntime from '@/app/components/charts/chartDowntime'
import HeaderSection from '@/app/components/header/sectionHeader'
import React from 'react'
import HistoryDowntime from './components/historyDowntime'
import ListMachine from './components/listMachine'
import TargetMtc from './components/targetMtc'

export default function Maintenance() {
  return (
    <main className="flex flex-row h-full w-full gap-5">
      <div className="basis-1/2 h-full bg-main-base rounded-lg flex flex-col gap-10">
        <HeaderSection name='HISTORY DOWNTIME' />
        <div className="col h-full flex flex-col gap-7 p-4">
          <div className="col h-[300px] border border-black rounded-lg">
            <ChartDowntime />
          </div>
          <HistoryDowntime />
        </div>
      </div>
       
      <div className="basis-1/2 flex flex-col gap-10">
        <div className="w-[680px] rounded-[8px] bg-main-base rounded-lg flex flex flex-col gap-[10px]" style={{height: '500px'}}>
          <HeaderSection name='DAFTAR MESIN STOP' />
          <HeaderSection name='(DALAM PERBAIKAN)' />
          <div className="col h-full p-4">
            <div className='col h-[70px] flex flex-row rounded bg-[#9DA5EE] justify-center mb-1'>
              <div className="basis-1/4 flex justify-center items-center h-full text-[40px]">NO MESIN</div>
              <div className="basis-2/4 flex justify-center items-center h-full text-[40px]">TGL RUSAK</div>
              <div className="basis-1/4 flex justify-center items-center h-full text-[40px]">DOWNTIME</div>
            </div>
            <div className="col h-[730px] overflow-y-auto">
              <ListMachine />
            </div>
          </div>
        </div>
        <div className="w-[680px] rounded-[8px] bg-main-base rounded-lg flex flex flex-col gap-[10px]" style={{height: '400px'}}>
          <TargetMtc />
        </div>
      </div>
    </main>
  )
}
