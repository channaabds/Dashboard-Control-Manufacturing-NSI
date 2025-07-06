
import HeaderSection from '@/app/components/header/sectionHeader'
import React from 'react'
import DataMesin from './dataMesin'


export default function TargetMtc() {
  return (
    <div className="w-[680px] rounded-[8px] bg-main-base flex flex-col gap-[10px]">
      <HeaderSection name='TARGET DAN ACTUAL' />
      <div className="w-[680px] rounded-[8px] bg-main-base flex flex-col gap-[10px]" style={{ height: '300px' }}>
        <div className='col h-[70px] flex flex-row rounded bg-[#9DA5EE] justify-center items-center mb-1'>
          <div className="basis-1/2 flex justify-center items-center h-full text-[40px]">TARGET</div>
          <div className="basis-1/2 flex justify-center items-center h-full text-[40px]">Bulan</div>
          <div className="basis-1/2 flex justify-center items-center h-full text-[40px]">ACTUAL</div>
        </div>
        <div className="col h-[730px] overflow-y-auto">
          <DataMesin />
        </div>
      </div>
    </div>
  );
}
