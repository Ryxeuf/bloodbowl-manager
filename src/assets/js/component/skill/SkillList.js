import {BooleanField, DatagridConfigurable, EditButton, InfiniteList, ShowButton, TextField, WrapperField} from "react-admin";
import {SkillFilterSidebar} from "./SkillFilterSidebar";

export const SkillList = props => (
    <InfiniteList {...props} exporter={false} aside={<SkillFilterSidebar/>}>
        <DatagridConfigurable
            bulkActionButtons={false}
            rowClick="expand"
            expand={<SkillPanel />}
            expandSingle
            omit={['description']}
        >
            <TextField source={"name"}/>
            <TextField source={"type"}/>
            <TextField source={"category"}/>
            <BooleanField source={"mandatory"}/>
            <TextField source={"description"} aria-multiline={true}/>
            <WrapperField label="Actions">
                <ShowButton label="" />
                <EditButton label="" />
            </WrapperField>
        </DatagridConfigurable>
    </InfiniteList>
);


const SkillPanel = () => {
    return <>
        <TextField source={"description"}/>
    </>;
}