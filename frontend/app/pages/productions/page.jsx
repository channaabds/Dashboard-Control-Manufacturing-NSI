import HeaderSection from '@/app/components/header/sectionHeader'
import React from 'react'
import HistoryLine from './components/historyLine';
import PercenSection from './components/percenSection';

export default async function Production() {
  return (
    <main className='flex flex-row gap-[25px]'>
      <PercenSection />
      <div className="w-[1115px] bg-main-base rounded-[8px] flex flex-col gap-[38px] p-[10px]">
        <HeaderSection name='HISTORY PRODUCTION' />
        <div className="col h-full flex flex-col gap-1">
          <div className='col h-[80px] flex flex-row rounded bg-[#9DA5EE] justify-center'>
            <div className="basis-1/5 flex justify-center items-center h-full text-[48px]">CAM</div>
            <div className="basis-1/5 flex justify-center items-center h-full text-[48px]">LINE 1</div>
            <div className="basis-1/5 flex justify-center items-center h-full text-[48px]">LINE 2</div>
            <div className="basis-1/5 flex justify-center items-center h-full text-[48px]">LINE 3</div>
            <div className="basis-1/5 flex justify-center items-center h-full text-[48px]">TGL</div>
          </div>
          <div className="col h-[730px] overflow-y-auto">
            <HistoryLine />
          </div>
        </div>
      </div>
    </main>
  )
}
