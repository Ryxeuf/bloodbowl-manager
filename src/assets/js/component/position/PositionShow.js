import {FieldGuesser, ShowGuesser} from "@api-platform/admin";
import {ChipField, NumberField, ReferenceArrayField, ReferenceField, SingleFieldList, TextField} from "react-admin";
import {Table, TableBody, TableCell, TableHead, TableRow} from "@mui/material";

export const PositionShowPanelHead = () => (
    <TableRow>
        <TableCell>Faction</TableCell>
        <TableCell>Qté</TableCell>
        <TableCell>M</TableCell>
        <TableCell>F</TableCell>
        <TableCell>AG</TableCell>
        <TableCell>CP</TableCell>
        <TableCell>AR</TableCell>
        <TableCell>Skills</TableCell>
        <TableCell>Cost</TableCell>
        <TableCell>Princip.</TableCell>
        <TableCell>Second.</TableCell>
    </TableRow>
);
export const PositionShowPanelRow = () => (

    <TableRow>
        <TableCell>
            <ReferenceField reference="factions" source="faction" link="show">
                <TextField source="name"/>
            </ReferenceField>
        </TableCell>
        <TableCell>
            <FieldGuesser source={"min"}/>-<FieldGuesser source={"max"}/>
        </TableCell>
        <TableCell>
            <FieldGuesser source={"m"}/>
        </TableCell>
        <TableCell>
            <FieldGuesser source={"f"}/>
        </TableCell>
        <TableCell>
            <FieldGuesser source={"ag"}/>
        </TableCell>
        <TableCell>
            <FieldGuesser source={"cp"}/>
        </TableCell>
        <TableCell>
            <FieldGuesser source={"ar"}/>
        </TableCell>
        <TableCell>
            <ReferenceArrayField label="Skills" reference="skills" source="skills">
                <SingleFieldList linkType="show">
                    <ChipField source="name"/>
                </SingleFieldList>
            </ReferenceArrayField>
        </TableCell>
        <TableCell>
            <NumberField source={"cost"}/>
        </TableCell>
        <TableCell>
            <FieldGuesser source={"primarySkills"}/>
        </TableCell>
        <TableCell>
            <FieldGuesser source={"secondarySkills"}/>
        </TableCell>
    </TableRow>
);

export const PositionShowPanel = props => (
    <Table>
        <TableHead>
            <PositionShowPanelHead/>
        </TableHead>
        <TableBody>
            <PositionShowPanelRow/>
        </TableBody>
    </Table>
);

export const PositionShow = props => (
    <ShowGuesser {...props}>
        <FieldGuesser source={"name"}/>
        <PositionShowPanel/>
    </ShowGuesser>
);
